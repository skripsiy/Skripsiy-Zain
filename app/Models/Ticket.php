<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ticket extends Model
{
    use LogsActivity;

    public $temp_category_path;
    public $temp_escalation_data;

    protected $primaryKey = 'idTicket';

    protected $with = ['customer', 'witelRelation.area', 'category'];
    
    protected $fillable = [
        'datereport', 'jenisTicket', 'notelpCust', 'password', 'namacust',
        'idlaporan', 'detailticket', 'gamas', 'lapul', 'gaul', 'resume',
        'klasifikasi', 'topic', 'topicDetail', 'noSC', 'statusSC',
        'validateClose', 'reasonnoODS', 'eksalasiTicket', 'eksalasiVia',
        'PIC', 'contact', 'responBE', 'description', 'reportedpriority',
        'datesolved', 'THT', 'status', 'regional', 'witel', 'condition',
        'assigned_to_user_id', 'solved_by_user_id', 'escalationStatus', 'resolved_by_agent', 'hasil_pengecekan', 'attachment',
        'customer_id', 'witel_id', 'category_id',
        // Routing fields
        'channel', 'source_system', 'pool_id', 'urgency_level', 'division_target', 'auto_assigned_at', 'sla_notified',
    ];

    protected $casts = [
        'datereport'      => 'date',
        'datesolved'      => 'date',
        'THT'             => 'datetime',
        'auto_assigned_at'=> 'datetime',
        'urgency_level'   => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Invalidasi cache dashboard setiap ada perubahan tiket.
     * Agar statistik dashboard tidak stale saat data berubah.
     */
    protected static function booted(): void
    {
        $clearCache = function ($ticket) {
            // Hapus cache Admin
            foreach (['today', 'week', 'month', 'quarter'] as $filter) {
                Cache::forget("admin_dashboard_stats_{$filter}");
                Cache::forget("admin_chart_{$filter}");
            }

            // Hapus cache Agent yang bersangkutan
            $agentIds = array_filter([$ticket->assigned_to_user_id, $ticket->solved_by_user_id]);
            foreach ($agentIds as $agentId) {
                foreach (['today', 'week', 'month', 'quarter'] as $filter) {
                    Cache::forget("agent_dashboard_stats_{$agentId}_{$filter}");
                }
            }

            // Hapus cache Team Leader (TL dashboard menggunakan md5 cache key dinamis)
            // Di lokal/testing, kita bisa menggunakan Cache::flush() jika menggunakan driver file/redis
            // untuk membersihkan sisa cache agar data selalu ter-update
            try {
                Cache::flush();
            } catch (\Exception $e) {
                // Fail-safe jika flush diblokir driver tertentu
            }
        };

        static::saving(function ($ticket) {
            // 1. Resolve Customer Data
            $name = null;
            $phone = null;
            $password = null;

            if (array_key_exists('namacust', $ticket->attributes)) {
                $name = $ticket->attributes['namacust'];
                unset($ticket->attributes['namacust']);
            }
            if (array_key_exists('notelpCust', $ticket->attributes)) {
                $phone = $ticket->attributes['notelpCust'];
                unset($ticket->attributes['notelpCust']);
            }
            if (array_key_exists('password', $ticket->attributes)) {
                $password = $ticket->attributes['password'];
                unset($ticket->attributes['password']);
            }

            if ($phone !== null || $name !== null) {
                $phone = $phone ?? '';
                $name = $name ?? '';
                
                $customer = Customer::firstOrCreate(
                    ['phone_number' => $phone],
                    ['name' => $name, 'password' => $password]
                );

                if (($name !== '' && $customer->name !== $name) || ($password !== null && $customer->password !== $password)) {
                    $customer->update(array_filter([
                        'name' => $name !== '' ? $name : $customer->name,
                        'password' => $password !== null ? $password : $customer->password,
                    ]));
                }

                $ticket->customer_id = $customer->id;
            }

            // 2. Resolve Geography (regional & witel)
            $witelName = null;
            $regionalName = null;

            if (array_key_exists('witel', $ticket->attributes)) {
                $witelName = $ticket->attributes['witel'];
                unset($ticket->attributes['witel']);
            }
            if (array_key_exists('regional', $ticket->attributes)) {
                $regionalName = $ticket->attributes['regional'];
                unset($ticket->attributes['regional']);
            }

            if ($witelName !== null) {
                $witelName = strtoupper(trim($witelName));
                $witel = Witel::where('name', $witelName)->first();

                if (!$witel && $witelName !== '') {
                    $areaId = 2; // Default to Pulau Jawa dan Bali
                    if ($regionalName !== null) {
                        $regUpper = strtoupper(trim($regionalName));
                        if (str_contains($regUpper, 'SUMA')) $areaId = 1;
                        elseif (str_contains($regUpper, 'KALI')) $areaId = 3;
                        elseif (str_contains($regUpper, 'SULA') || str_contains($regUpper, 'MALU') || str_contains($regUpper, 'PAPU') || str_contains($regUpper, 'KTI')) $areaId = 4;
                    }

                    $witel = Witel::create([
                        'area_id' => $areaId,
                        'name' => $witelName
                    ]);
                }

                if ($witel) {
                    $ticket->witel_id = $witel->id;
                }
            }

            // 3. Resolve Ticket Category Hierarchy
            $jenisTicket = null;
            $klasifikasi = null;
            $topic = null;
            $topicDetail = null;

            if (array_key_exists('jenisTicket', $ticket->attributes)) {
                $jenisTicket = $ticket->attributes['jenisTicket'];
                unset($ticket->attributes['jenisTicket']);
            }
            if (array_key_exists('klasifikasi', $ticket->attributes)) {
                $klasifikasi = $ticket->attributes['klasifikasi'];
                unset($ticket->attributes['klasifikasi']);
            }
            if (array_key_exists('topic', $ticket->attributes)) {
                $topic = $ticket->attributes['topic'];
                unset($ticket->attributes['topic']);
            }
            if (array_key_exists('topicDetail', $ticket->attributes)) {
                $topicDetail = $ticket->attributes['topicDetail'];
                unset($ticket->attributes['topicDetail']);
            }

            if ($jenisTicket !== null || $klasifikasi !== null || $topic !== null || $topicDetail !== null) {
                $currentJenis = $jenisTicket ?? $ticket->jenisTicket;
                $currentKlasifikasi = $klasifikasi ?? $ticket->klasifikasi;
                $currentTopic = $topic ?? $ticket->topic;
                $currentDetail = $topicDetail ?? $ticket->topicDetail;

                $lastCategoryId = null;

                $resolveCategory = function ($name, $parentId = null) {
                    $name = trim($name);
                    if ($name === '') return null;

                    $cat = TicketCategory::where('name', $name)->where('parent_id', $parentId)->first();
                    if ($cat) {
                        return $cat->id;
                    }

                    $cat = TicketCategory::create([
                        'name' => $name,
                        'parent_id' => $parentId
                    ]);

                    return $cat->id;
                };

                if ($currentJenis) {
                    $lastCategoryId = $resolveCategory($currentJenis, null);
                    if ($lastCategoryId && $currentKlasifikasi) {
                        $lastCategoryId = $resolveCategory($currentKlasifikasi, $lastCategoryId);
                        if ($lastCategoryId && $currentTopic) {
                            $lastCategoryId = $resolveCategory($currentTopic, $lastCategoryId);
                            if ($lastCategoryId && $currentDetail) {
                                $lastCategoryId = $resolveCategory($currentDetail, $lastCategoryId);
                            }
                        }
                    }
                }

                $ticket->category_id = $lastCategoryId;
            }

            // 4. Buffer Escalation Data
            $escData = [];
            $hasEscChange = false;

            $fieldsMap = [
                'eksalasiTicket' => 'eksalasiTicket',
                'eksalasiVia' => 'escalated_via',
                'PIC' => 'escalated_to',
                'contact' => 'contact',
                'responBE' => 'respon_be',
                'escalationStatus' => 'status'
            ];

            foreach ($fieldsMap as $oldKey => $newKey) {
                if (array_key_exists($oldKey, $ticket->attributes)) {
                    $escData[$newKey] = $ticket->attributes[$oldKey];
                    $hasEscChange = true;
                    unset($ticket->attributes[$oldKey]);
                }
            }

            if ($hasEscChange) {
                $ticket->temp_escalation_data = $escData;
            }
        });

        static::saved(function ($ticket) use ($clearCache) {
            $clearCache($ticket);

            // Save buffered escalation data
            if (isset($ticket->temp_escalation_data)) {
                $data = $ticket->temp_escalation_data;
                $latest = $ticket->escalations()->latest()->first();

                $escTo = array_key_exists('escalated_to', $data) ? $data['escalated_to'] : ($latest ? $latest->escalated_to : null);
                $escVia = array_key_exists('escalated_via', $data) ? $data['escalated_via'] : ($latest ? $latest->escalated_via : null);
                $escContact = array_key_exists('contact', $data) ? $data['contact'] : ($latest ? $latest->contact : null);
                $escRespon = array_key_exists('respon_be', $data) ? $data['respon_be'] : ($latest ? $latest->respon_be : null);
                $escStatus = array_key_exists('status', $data) ? $data['status'] : ($latest ? $latest->status : null);
                $eksalasiTicket = array_key_exists('eksalasiTicket', $data) ? $data['eksalasiTicket'] : ($latest ? 'Yes' : 'No');

                if ($eksalasiTicket === 'Yes' || $escTo || $escVia || $escContact || $escRespon || $escStatus) {
                    $ticket->escalations()->create([
                        'escalated_to' => $escTo,
                        'escalated_via' => $escVia,
                        'contact' => $escContact,
                        'respon_be' => $escRespon,
                        'status' => $escStatus,
                    ]);
                }

                unset($ticket->temp_escalation_data);
            }
        });

        static::deleted(function ($ticket) use ($clearCache) {
            $clearCache($ticket);
        });
    }

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function escalations()
    {
        return $this->hasMany(TicketEscalation::class, 'ticket_id');
    }

    public function witelRelation()
    {
        return $this->belongsTo(Witel::class, 'witel_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Customer Accessors
     */
    public function getNamacustAttribute()
    {
        if (array_key_exists('namacust', $this->attributes)) {
            return $this->attributes['namacust'];
        }
        return $this->customer ? $this->customer->name : null;
    }

    public function getNotelpCustAttribute()
    {
        if (array_key_exists('notelpCust', $this->attributes)) {
            return $this->attributes['notelpCust'];
        }
        return $this->customer ? $this->customer->phone_number : null;
    }

    public function getPasswordAttribute()
    {
        if (array_key_exists('password', $this->attributes)) {
            return $this->attributes['password'];
        }
        return $this->customer ? $this->customer->password : null;
    }

    /**
     * Geography Accessors
     */
    public function getRegionalAttribute()
    {
        if (array_key_exists('regional', $this->attributes)) {
            return $this->attributes['regional'];
        }
        return $this->witelRelation && $this->witelRelation->area ? $this->witelRelation->area->name : null;
    }

    public function getWitelAttribute()
    {
        if (array_key_exists('witel', $this->attributes)) {
            return $this->attributes['witel'];
        }
        return $this->witelRelation ? $this->witelRelation->name : null;
    }

    /**
     * Category Accessors
     */
    public function getJenisTicketAttribute()
    {
        if (array_key_exists('jenisTicket', $this->attributes)) {
            return $this->attributes['jenisTicket'];
        }
        if (!$this->category) return null;
        $path = $this->getCategoryPath();
        return $path[0] ?? null;
    }

    public function getKlasifikasiAttribute()
    {
        if (array_key_exists('klasifikasi', $this->attributes)) {
            return $this->attributes['klasifikasi'];
        }
        if (!$this->category) return null;
        $path = $this->getCategoryPath();
        return $path[1] ?? null;
    }

    public function getTopicAttribute()
    {
        if (array_key_exists('topic', $this->attributes)) {
            return $this->attributes['topic'];
        }
        if (!$this->category) return null;
        $path = $this->getCategoryPath();
        return $path[2] ?? null;
    }

    public function getTopicDetailAttribute()
    {
        if (array_key_exists('topicDetail', $this->attributes)) {
            return $this->attributes['topicDetail'];
        }
        if (!$this->category) return null;
        $path = $this->getCategoryPath();
        return $path[3] ?? null;
    }

    private function getCategoryPath()
    {
        if (isset($this->temp_category_path)) {
            return $this->temp_category_path;
        }

        $path = [];
        $current = $this->category;
        while ($current) {
            $path[] = $current->name;
            $current = $current->parent;
        }
        $path = array_reverse($path);
        $this->temp_category_path = $path;
        return $path;
    }

    /**
     * Escalation Accessors
     */
    public function getEksalasiTicketAttribute()
    {
        if (array_key_exists('eksalasiTicket', $this->attributes)) {
            return $this->attributes['eksalasiTicket'];
        }
        return $this->escalations->isNotEmpty() ? 'Yes' : 'No';
    }

    public function getEksalasiViaAttribute()
    {
        if (array_key_exists('eksalasiVia', $this->attributes)) {
            return $this->attributes['eksalasiVia'];
        }
        $latest = $this->escalations->sortByDesc('created_at')->first();
        return $latest ? $latest->escalated_via : null;
    }

    public function getPicAttribute()
    {
        if (array_key_exists('PIC', $this->attributes)) {
            return $this->attributes['PIC'];
        }
        $latest = $this->escalations->sortByDesc('created_at')->first();
        return $latest ? $latest->escalated_to : null;
    }

    public function getContactAttribute()
    {
        if (array_key_exists('contact', $this->attributes)) {
            return $this->attributes['contact'];
        }
        $latest = $this->escalations->sortByDesc('created_at')->first();
        return $latest ? $latest->contact : null;
    }

    public function getResponBEAttribute()
    {
        if (array_key_exists('responBE', $this->attributes)) {
            return $this->attributes['responBE'];
        }
        $latest = $this->escalations->sortByDesc('created_at')->first();
        return $latest ? $latest->respon_be : null;
    }

    public function getEscalationStatusAttribute()
    {
        if (array_key_exists('escalationStatus', $this->attributes)) {
            return $this->attributes['escalationStatus'];
        }
        $latest = $this->escalations->sortByDesc('created_at')->first();
        return $latest ? $latest->status : null;
    }

    /**
     * Get the user this ticket is assigned to
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the user who solved this ticket
     */
    public function solvedBy()
    {
        return $this->belongsTo(User::class, 'solved_by_user_id');
    }

    // ─────────────────────────────────────────────
    // Urgency Helpers
    // ─────────────────────────────────────────────

    /**
     * Label urgency tier berdasarkan urgency_level (1–5)
     */
    public function getUrgencyLabelAttribute(): string
    {
        return match ((int) $this->urgency_level) {
            5 => 'VVIP/Management',
            4 => 'HVC',
            3 => 'Super Emergency',
            2 => 'Emergency',
            default => 'Low Emergency',
        };
    }

    /**
     * CSS class untuk badge urgency
     */
    public function getUrgencyBadgeClassAttribute(): string
    {
        return match ((int) $this->urgency_level) {
            5 => 'urgency-vvip',
            4 => 'urgency-hvc',
            3 => 'urgency-super',
            2 => 'urgency-emergency',
            default => 'urgency-low',
        };
    }

    /**
     * Label nama divisi target
     */
    public function getDivisionLabelAttribute(): string
    {
        return match ($this->division_target) {
            'area'     => 'Area',
            'besfixed' => 'Besfixed',
            'saltik'   => 'Saltik',
            default    => '-',
        };
    }

    /**
     * Accessor untuk solvedby (legacy string column)
     */
    public function getSolvedbyAttribute()
    {
        if ($this->condition === 'Closed' && is_null($this->solved_by_user_id)) {
            return 'System Auto Sync (Mock)';
        }

        $solvedBy = $this->relationLoaded('solvedBy')
            ? $this->getRelation('solvedBy')
            : $this->solvedBy()->getResults();

        return $solvedBy ? $solvedBy->name : null;
    }
}



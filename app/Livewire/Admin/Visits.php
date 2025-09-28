<?php

namespace App\Livewire\Admin;

use App\Models\Visit;
use App\Models\BannedIp;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Visits extends Component
{
    use WithPagination;
    
    protected $listeners = ['confirmBanIp', 'confirmUnbanIp'];
    
    public $search = '';
    public $sortField = 'last_visit';
    public $sortDirection = 'desc';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'last_visit'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }
    
    public function banIp($ipAddress)
    {
        // Vérifier si l'IP n'est pas déjà bannie
        if (BannedIp::isIpBanned($ipAddress)) {
            $this->dispatch('toast', __('admin.visits.messages.ip_already_banned'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.visits.confirm.ban_ip_title'),
            'message' => __('admin.visits.confirm.ban_ip_message', ['ip' => $ipAddress]),
            'confirmButtonText' => __('admin.visits.confirm.ban_ip_button'),
            'cancelButtonText' => __('admin.visits.confirm.cancel'),
            'confirmAction' => 'confirmBanIp',
            'params' => [$ipAddress],
            'icon' => 'ban',
            'iconColor' => 'red'
        ]);
    }
    
    public function confirmBanIp($params)
    {
        $ipAddress = $params[0];
        
        // Vérifier à nouveau si l'IP n'est pas déjà bannie
        if (BannedIp::isIpBanned($ipAddress)) {
            $this->dispatch('toast', __('admin.visits.messages.ip_already_banned'), 'error');
            return;
        }
        
        // Bannir l'IP
        BannedIp::create([
            'ip_address' => $ipAddress,
            'reason' => __('admin.visits.messages.ban_reason_from_visits'),
            'banned_by' => auth()->id(),
        ]);
        
        $this->dispatch('toast', __('admin.visits.messages.ip_banned_successfully', ['ip' => $ipAddress]), 'success');
    }
    
    public function unbanIp($ipAddress)
    {
        // Vérifier si l'IP est bien bannie
        if (!BannedIp::isIpBanned($ipAddress)) {
            $this->dispatch('toast', __('admin.visits.messages.ip_not_banned'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.visits.confirm.unban_ip_title'),
            'message' => __('admin.visits.confirm.unban_ip_message', ['ip' => $ipAddress]),
            'confirmButtonText' => __('admin.visits.confirm.unban_ip_button'),
            'cancelButtonText' => __('admin.visits.confirm.cancel'),
            'confirmAction' => 'confirmUnbanIp',
            'params' => [$ipAddress],
            'icon' => 'check',
            'iconColor' => 'green'
        ]);
    }
    
    public function confirmUnbanIp($params)
    {
        $ipAddress = $params[0];
        
        // Vérifier à nouveau si l'IP est bien bannie
        if (!BannedIp::isIpBanned($ipAddress)) {
            $this->dispatch('toast', __('admin.visits.messages.ip_not_banned'), 'error');
            return;
        }
        
        // Débannir l'IP
        BannedIp::where('ip_address', $ipAddress)->delete();
        
        $this->dispatch('toast', __('admin.visits.messages.ip_unbanned_successfully', ['ip' => $ipAddress]), 'success');
    }
    
    public function render()
    {
        // Group visits by IP address and count them
        $visits = DB::table('visits')
            ->select('ip_address', 'country', DB::raw('count(*) as total_visits'), DB::raw('MAX(visited_at) as last_visit'))
            ->when($this->search, function ($query) {
                $query->where('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('country', 'like', '%' . $this->search . '%');
            })
            ->groupBy('ip_address', 'country')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Récupérer les IPs bannies pour affichage visuel
        $bannedIps = BannedIp::pluck('ip_address')->toArray();

        return view('livewire.admin.visits', [
            'visits' => $visits,
            'bannedIps' => $bannedIps,
        ]);
    }
}
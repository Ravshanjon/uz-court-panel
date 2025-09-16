<?php
// app/Http/Livewire/Countdown.php
namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Countdown extends Component
{
    public string $until;
    public bool $expired = false;
    public string $diff = '';

    public function mount(string $until) { $this->until = $until; $this->tick(); }
    public function tick()
    {
        $u = Carbon::parse($this->until); $now = now();
        if ($now->gte($u)) { $this->expired = true; $this->diff = 'Expired'; return; }
        $sec = $u->diffInSeconds($now);
        $d = intdiv($sec, 86400); $sec%=86400; $h=intdiv($sec,3600); $sec%=3600; $m=intdiv($sec,60); $s=$sec%60;
        $this->diff = sprintf('%dd %02d:%02d:%02d', $d,$h,$m,$s);
    }
    public function render() { return view('livewire.countdown'); }
}

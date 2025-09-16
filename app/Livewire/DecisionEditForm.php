<?php
namespace App\Livewire;

use App\Models\Appeal;
use App\Models\TypeOfDecision;
use Livewire\Component;

class DecisionEditForm extends Component
{
    public $recordId;
    public $type_of_decision_id;


    #[OnMount]
    public function mount($recordId)
    {
        $this->recordId = $recordId;
        $this->type_of_decision_id = Appeal::find($recordId)?->type_of_decision_id;
    }

    public function save()
    {
        $appeal = Appeal::findOrFail($this->recordId);
        $appeal->type_of_decision_id = $this->type_of_decision_id;
        $appeal->save();

        $this->dispatch('close-modal');
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.decision-edit-form', [
            'decisions' => TypeOfDecision::pluck('name', 'id'),
        ]);
    }
}

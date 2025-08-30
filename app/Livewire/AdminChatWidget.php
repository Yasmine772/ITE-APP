<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;

class AdminChatWidget extends Component
{
    public $receiverId;
    public $messageText = '';
    public $messages = [];
    public $teachers = [];

    protected $listeners = ['refreshMessages' => '$refresh'];

    public function mount()
    {
        $this->teachers = User::role('teacher')->get();
    }

    public function selectTeacher($id)
    {
        $this->receiverId = $id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if(!$this->receiverId) return;

        $this->messages = Message::where(function($q){
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $this->receiverId);
        })->orWhere(function($q){
            $q->where('sender_id', $this->receiverId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();
    }

    public function sendMessage()
    {
        if(!$this->messageText || !$this->receiverId) return;

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverId,
            'content' => $this->messageText,
        ]);

        $this->messageText = '';
        $this->loadMessages();

        $this->emit('refreshMessages');
    }

    public function render()
    {
        return view('livewire.admin-chat-widget');
    }
}


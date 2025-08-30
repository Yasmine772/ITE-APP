<div class="bg-white p-4 rounded-2xl shadow flex h-[400px]">

    <div class="w-1/4 border-r pr-2 overflow-y-auto">
        <h3 class="font-bold mb-2">Teachers</h3>
        @foreach($teachers as $teacher)
            <div wire:click="selectTeacher({{ $teacher->id }})"
                 class="cursor-pointer hover:bg-gray-100 p-2 rounded {{ $receiverId == $teacher->id ? 'bg-gray-200' : '' }}">
                {{ $teacher->name }}
            </div>
        @endforeach
    </div>

    <div class="flex-1 pl-2 flex flex-col">
        @if($receiverId)
            <div class="h-full overflow-y-auto mb-2 flex-1" wire:poll.3s>
                @foreach($messages as $msg)
                    <div class="mb-1 {{ $msg->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                        <span class="inline-block bg-gray-200 px-3 py-1 rounded-xl">
                            {{ $msg->content }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="flex mt-2">
                <input type="text" wire:model.defer="messageText" class="flex-1 border rounded-l-xl p-2" placeholder="Type a message">
                <button wire:click="sendMessage" class="bg-blue-500 text-white px-4 rounded-r-xl">Send</button>
            </div>
        @else
            <p>Select a teacher to start chatting.</p>
        @endif
    </div>

</div>

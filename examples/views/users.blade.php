<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <input
                type="text"
                class="input input-bordered input-sm w-48"
                placeholder="検索..."
                wire:model.live.debounce.300ms="search"
            />
        </div>
    </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" x-data="{ open: false }" x-on:created.window="open = false" x-on:modifying.window="open = false">
            <div
                class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer border-2 border-dashed border-base-300 hover:border-primary/50 h-40"
                @click="open = true; $wire.modifying = null; $wire.name = ''; $wire.email = ''; $wire.$refresh()"
                x-show="!open"
            >
                <div class="card-body items-center justify-center text-center h-full">
                    <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-base-content/50">
                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="card bg-base-100 shadow-md h-40"
                x-show="open"
                x-cloak
            >
                <div class="card-body p-4 h-full flex flex-col">
                    <form wire:submit="create" class="flex flex-col h-full">
                        <div class="flex gap-3 flex-1 items-start pt-1">
                            <div class="avatar placeholder">
                                <div class="bg-primary/20 text-primary rounded-full w-10 h-10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col gap-2 pt-1">
                                <input
                                    type="text"
                                    class="input input-bordered input-sm w-full @error('name') input-error @enderror"
                                    placeholder="名前"
                                    wire:model="name"
                                />
                                <input
                                    type="email"
                                    class="input input-bordered input-sm w-full @error('email') input-error @enderror"
                                    placeholder="メール"
                                    wire:model="email"
                                />
                                <div class="h-4 overflow-hidden">
                                    @error('name')<p class="text-xs text-error truncate">{{ $message }}</p>@enderror
                                    @error('email')<p class="text-xs text-error truncate">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-auto">
                            <button type="button" class="btn btn-ghost btn-sm btn-circle" @click="open = false; $wire.name = ''; $wire.email = ''" title="キャンセル">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm btn-circle" wire:loading.attr="disabled" title="作成">
                                <span wire:loading.remove wire:target="create">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span wire:loading wire:target="create" class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

                @foreach($this->users as $user)
            <div
                class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 relative h-40"
                wire:key="user-{{ $user->id }}"
                x-data
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                                @if($deleting === $user->id)
                    <div
                        class="absolute inset-0 bg-black/50 backdrop-blur-sm rounded-2xl z-10 flex items-center justify-center"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        <div class="bg-base-100 rounded-xl p-3 shadow-lg">
                            <div class="flex justify-center gap-2">
                                <button class="btn btn-ghost btn-sm btn-circle" wire:click="cancel" title="キャンセル">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                    </svg>
                                </button>
                                <button class="btn btn-ghost btn-sm btn-circle text-error" wire:click="delete({{ $user->id }})" title="削除">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.519.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card-body p-4 h-full flex flex-col">
                    @if($modifying === $user->id)
                                                <form wire:submit="update" class="flex flex-col h-full">
                            <div class="flex gap-3 flex-1 items-start pt-1">
                                <div class="avatar placeholder">
                                    <div class="flex items-center justify-center bg-primary text-primary-content rounded-full w-10 h-10">
                                        <span>{{ $this->avatar($name ?: $user->name) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 flex flex-col gap-2 pt-1">
                                    <input
                                        type="text"
                                        class="input input-bordered input-sm w-full @error('name') input-error @enderror"
                                        wire:model="name"
                                    />
                                    <input
                                        type="email"
                                        class="input input-bordered input-sm w-full @error('email') input-error @enderror"
                                        wire:model="email"
                                    />
                                    <div class="h-4 overflow-hidden">
                                        @error('name')<p class="text-xs text-error truncate">{{ $message }}</p>@enderror
                                        @error('email')<p class="text-xs text-error truncate">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-auto">
                                <button type="button" class="btn btn-ghost btn-sm btn-circle" wire:click="revert" title="キャンセル">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                    </svg>
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm btn-circle" wire:loading.attr="disabled" title="保存">
                                    <span wire:loading.remove wire:target="update">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="update" class="loading loading-spinner loading-sm"></span>
                                </button>
                            </div>
                        </form>
                    @else
                                                <div class="flex gap-3 flex-1 items-start pt-1">
                            <div class="avatar placeholder">
                                <div class="flex items-center justify-center bg-primary text-primary-content rounded-full w-10 h-10">
                                    <span>{{ $this->avatar($user->name) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate">{{ $user->name }}</p>
                                <p class="text-sm text-base-content/60 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-1 mt-auto">
                            <button class="btn btn-ghost btn-sm btn-circle" wire:click="modify({{ $user->id }})" title="変更">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                </svg>
                            </button>
                            <button class="btn btn-ghost btn-sm btn-circle text-error" wire:click="select({{ $user->id }})" title="削除">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.519.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

        @if($this->users->isEmpty())
        <div class="text-center py-12 text-base-content/50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-4 opacity-50">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <p>ユーザーが見つかりません</p>
        </div>
    @endif
</div>

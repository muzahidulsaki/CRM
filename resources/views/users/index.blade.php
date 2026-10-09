<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('User & Team Accounts') }}
                </h2>
                <span class="text-sm text-gray-500">Manage user credentials and assigned system roles</span>
            </div>
            <button @click="$dispatch('open-modal', 'add-user-modal')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New User
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Users Table Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Name & Contact</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Joined Date</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm me-3">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                @if($user->phone)
                                                    <div class="text-xs text-gray-400">{{ $user->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            {{ $user->role->name ?? 'None' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Active
                                            </span>
                                        @elseif($user->status === 'inactive')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                Inactive
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $user->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button 
                                            x-data=""
                                            @click="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit User Modal -->
                                <x-modal name="edit-user-modal-{{ $user->id }}" :show="false" focusable>
                                    <form method="POST" action="{{ route('users.update', $user) }}" class="p-6">
                                        @csrf
                                        @method('PUT')

                                        <h3 class="text-lg font-bold text-gray-900 mb-4">Edit User: {{ $user->name }}</h3>

                                        <div class="space-y-4">
                                            <div>
                                                <x-input-label for="edit_name_{{ $user->id }}" value="Full Name" />
                                                <x-text-input id="edit_name_{{ $user->id }}" name="name" type="text" class="mt-1 block w-full" value="{{ $user->name }}" required />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_phone_{{ $user->id }}" value="Phone Number" />
                                                <x-text-input id="edit_phone_{{ $user->id }}" name="phone" type="text" class="mt-1 block w-full" value="{{ $user->phone }}" />
                                            </div>

                                            <div>
                                                <x-input-label for="edit_role_{{ $user->id }}" value="Assigned Role" />
                                                <select id="edit_role_{{ $user->id }}" name="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $user->role_id === $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }} ({{ $role->slug }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <x-input-label for="edit_status_{{ $user->id }}" value="Account Status" />
                                                <select id="edit_status_{{ $user->id }}" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                </select>
                                            </div>

                                            <div>
                                                <x-input-label for="edit_password_{{ $user->id }}" value="New Password (leave empty to keep current)" />
                                                <x-text-input id="edit_password_{{ $user->id }}" name="password" type="password" class="mt-1 block w-full" />
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                            <x-primary-button>Update User</x-primary-button>
                                        </div>
                                    </form>
                                </x-modal>

                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- Add User Modal -->
            <x-modal name="add-user-modal" :show="false" focusable>
                <form method="POST" action="{{ route('users.store') }}" class="p-6">
                    @csrf

                    <h3 class="text-lg font-bold text-gray-900 mb-4">Create New Team User</h3>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email Address" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Phone / WhatsApp Number" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" placeholder="+8801..." />
                        </div>

                        <div>
                            <x-input-label for="role_id" value="Assigned Role" />
                            <select id="role_id" name="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="password" value="Initial Password" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                        <x-primary-button>Create Account</x-primary-button>
                    </div>
                </form>
            </x-modal>

        </div>
    </div>
</x-app-layout>

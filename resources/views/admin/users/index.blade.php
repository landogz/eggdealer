@extends('admin.layouts.app')

@section('title', 'Users')
@section('header_title', 'Users')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">User management</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Manage admin and staff accounts.</p>
            </div>
            <button type="button" id="openUserModal" class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add user
            </button>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-900/50">
            <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr class="border-t border-slate-100 dark:border-slate-700">
                            <td class="px-4 py-2 font-medium text-slate-900 dark:text-slate-50">{{ $u->name }}</td>
                            <td class="px-4 py-2">{{ $u->email }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-[0.7rem] font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ $u->role ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" class="editUserBtn rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-gold hover:text-gold dark:border-slate-600 dark:bg-slate-800"
                                        data-id="{{ $u->id }}"
                                        data-name="{{ $u->name }}"
                                        data-email="{{ $u->email }}"
                                        data-role="{{ $u->role ?? 'admin' }}">
                                    Edit
                                </button>
                                @if ($u->id !== auth()->id())
                                    <button type="button" class="deleteUserBtn ml-1.5 rounded-full border border-red-100 bg-white px-3 py-1 text-[0.7rem] font-medium text-red-600 shadow-sm hover:border-red-300 dark:border-slate-600 dark:bg-slate-800 dark:text-red-400"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Add / Edit User Modal -->
    <div id="userModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl dark:bg-slate-900 dark:text-slate-100">
            <div class="flex items-center justify-between">
                <h2 id="userModalTitle" class="text-sm font-semibold text-slate-900 dark:text-slate-50">Add user</h2>
                <button type="button" id="closeUserModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-lg leading-none">&times;</button>
            </div>
            <form id="userForm" class="mt-4 space-y-3">
                <input type="hidden" id="userId" name="user_id" value="">
                <div>
                    <label for="userName" class="text-xs font-medium text-slate-700 dark:text-slate-300">Name</label>
                    <input id="userName" type="text" required class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label for="userEmail" class="text-xs font-medium text-slate-700 dark:text-slate-300">Email</label>
                    <input id="userEmail" type="email" required class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label for="userPassword" class="text-xs font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input id="userPassword" type="password" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="Leave blank to keep current (edit mode)">
                </div>
                <div>
                    <label for="userPasswordConfirm" class="text-xs font-medium text-slate-700 dark:text-slate-300">Confirm password</label>
                    <input id="userPasswordConfirm" type="password" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="Required when adding user">
                </div>
                <div>
                    <label for="userRole" class="text-xs font-medium text-slate-700 dark:text-slate-300">Role</label>
                    <select id="userRole" required class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        <option value="admin">Admin</option>
                        <option value="inventory_manager">Inventory Manager</option>
                    </select>
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelUser" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800">Cancel</button>
                    <button type="submit" id="saveUser" class="ml-2 rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('userModal');
    var titleEl = document.getElementById('userModalTitle');
    var idInput = document.getElementById('userId');
    var form = document.getElementById('userForm');
    var passwordInput = document.getElementById('userPassword');
    var passwordConfirmInput = document.getElementById('userPasswordConfirm');

    function openModal(mode, data) {
        if (!modal) return;
        if (mode === 'add') {
            titleEl.textContent = 'Add user';
            idInput.value = '';
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            passwordInput.value = '';
            passwordConfirmInput.value = '';
            passwordInput.required = true;
            passwordConfirmInput.required = true;
            document.getElementById('userRole').value = 'admin';
        } else if (mode === 'edit' && data) {
            titleEl.textContent = 'Edit user';
            idInput.value = data.id;
            document.getElementById('userName').value = data.name || '';
            document.getElementById('userEmail').value = data.email || '';
            passwordInput.value = '';
            passwordConfirmInput.value = '';
            passwordInput.required = false;
            passwordConfirmInput.required = false;
            document.getElementById('userRole').value = data.role || 'admin';
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    document.getElementById('openUserModal').addEventListener('click', function () { openModal('add'); });
    document.getElementById('closeUserModal').addEventListener('click', closeModal);
    document.getElementById('cancelUser').addEventListener('click', closeModal);

    document.querySelectorAll('.editUserBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal('edit', {
                id: btn.dataset.id,
                name: btn.dataset.name,
                email: btn.dataset.email,
                role: btn.dataset.role,
            });
        });
    });

    document.querySelectorAll('.deleteUserBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.id;
            var name = btn.dataset.name;
            Swal.fire({
                icon: 'warning',
                title: 'Delete user?',
                text: 'Are you sure you want to delete ' + name + '?',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Yes, delete',
            }).then(function (result) {
                if (!result.isConfirmed) return;
                axios.delete('{{ url('admin/users') }}/' + id)
                    .then(function () {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'User deleted.', timer: 1500, showConfirmButton: false });
                        setTimeout(function () { window.location.reload(); }, 1500);
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Could not delete user.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
                    });
            });
        });
    });

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var id = idInput.value;
            var payload = {
                name: document.getElementById('userName').value.trim(),
                email: document.getElementById('userEmail').value.trim(),
                role: document.getElementById('userRole').value,
            };
            var pwd = passwordInput.value;
            if (id) {
                if (pwd) {
                    payload.password = pwd;
                    payload.password_confirmation = passwordConfirmInput ? passwordConfirmInput.value : pwd;
                }
            } else {
                payload.password = pwd;
                payload.password_confirmation = passwordConfirmInput ? passwordConfirmInput.value : pwd;
            }
            var saveBtn = document.getElementById('saveUser');
            try {
                if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
                if (id) {
                    await axios.put('{{ url('admin/users') }}/' + id, payload);
                } else {
                    await axios.post('{{ route('admin.users.store') }}', payload);
                }
                Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'User updated.' : 'User created.', timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : (err.response && err.response.data && err.response.data.errors ? JSON.stringify(err.response.data.errors) : 'Please check the form.');
                Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush

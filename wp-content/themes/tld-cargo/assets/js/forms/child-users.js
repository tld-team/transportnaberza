// Sample data - replace with actual data from your backend
let users = [
    {id: 1, firstName: 'John', lastName: 'Doe', username: 'johndoe', phone: '123456789', role: 'contributor', active: true},
    {id: 2, firstName: 'Jane', lastName: 'Smith', username: 'janesmith', phone: '987654321', role: 'author', active: true}
];

// Initialize modal
const userFormModal = new bootstrap.Modal(document.getElementById('userFormModal'));
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

// Load users when page loads
document.addEventListener('DOMContentLoaded', loadUsers);

function loadUsers() {
    const tableBody = document.getElementById('usersTableBody');
    tableBody.innerHTML = '';

    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.firstName} ${user.lastName}</td>
            <td>${user.username}</td>
            <td>${user.phone}</td>
            <td class="w-auto">
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(${user.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary me-1" onclick="toggleUserStatus(${user.id}, ${!user.active})">
                    <i class="bi ${user.active ? 'bi-eye-slash' : 'bi-eye'}"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(${user.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function openUserForm(userId = null) {
    const form = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');

    if (userId) {
        // Edit mode
        modalTitle.textContent = 'Edit User';
        const user = users.find(u => u.id === userId);
        document.getElementById('userId').value = user.id;
        document.getElementById('firstName').value = user.firstName;
        document.getElementById('lastName').value = user.lastName;
        document.getElementById('username').value = user.username;
        document.getElementById('phone').value = user.phone;
        document.getElementById('password').placeholder = 'Leave blank to keep current';
    } else {
        // Add mode
        modalTitle.textContent = 'Add User';
        form.reset();
        document.getElementById('password').placeholder = '';
    }

    userFormModal.show();
}

function saveUser() {
    const userId = document.getElementById('userId').value;
    const userData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        username: document.getElementById('username').value,
        phone: document.getElementById('phone').value,
        password: document.getElementById('password').value
    };

    // Validate
    if (!userData.firstName || !userData.lastName || !userData.username) {
        alert('Please fill in all required fields');
        return;
    }

    // In a real app, you would send this to your backend
    if (userId) {
        // Update existing user
        const index = users.findIndex(u => u.id === parseInt(userId));
        users[index] = {...users[index], ...userData};
    } else {
        // Add new user
        const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
        users.push({
            id: newId,
            ...userData,
            active: true
        });
    }

    // Primer korišćenja:
    create_wp_user({
        username: userData.username,
        email: userData.email,
        password: userData.password,
        first_name: userData.firstName,
        last_name: userData.lastName,
        phone: userData.phone,
        nonce: 'sS8yMjPQ5EEJ7Qa' // Morate generisati nonce u PHP-u
    }, function(response) {
        if (response.success) {
            console.log('Korisnik uspešno kreiran! ID:', response.user_id);
            // Osveži listu korisnika ili prikaži poruku o uspehu
        } else {
            console.error('Greška:', response.message);
            // Prikaži grešku korisniku
        }
    });
    userFormModal.hide();
    loadUsers();
}

function editUser(userId) {
    openUserForm(userId);
}

function toggleUserStatus(userId, newStatus) {
    // In a real app, you would call your backend here
    const user = users.find(u => u.id === userId);
    user.active = newStatus;
    loadUsers();
}

function confirmDelete(userId) {
    document.getElementById('confirmMessage').textContent = 'Are you sure you want to delete this user?';
    document.getElementById('confirmAction').onclick = function() {
        deleteUser(userId);
    };
    confirmModal.show();
}

function deleteUser(userId) {
    // In a real app, you would call your backend here
    users = users.filter(u => u.id !== userId);
    confirmModal.hide();
    loadUsers();
}


/**
 * Kreira WordPress korisnika preko AJAX-a
 * @param {Object} userData - Objekat sa podacima korisnika
 * @param {Function} callback - Callback funkcija koja se poziva nakon kreiranja
 */
function create_wp_user(userData, callback) {
    // Validacija osnovnih podataka
    if (!userData.username || !userData.email) {
        if (callback) callback({success: false, message: 'Username and email are required'});
        return;
    }

    // AJAX poziv ka WordPress backendu
    jQuery.ajax({
        url: ajaxurl, // ajaxurl je definisan u WordPress adminu
        type: 'POST',
        data: {
            action: 'create_custom_user',
            nonce: userData.nonce, // Security nonce
            user_data: {
                username: userData.username,
                email: userData.email,
                password: userData.password || '', // Ako nije prosleđeno, WP generiše automatski
                first_name: userData.first_name || '',
                last_name: userData.last_name || '',
                phone: userData.phone || '',
            }
        },
        success: function(response) {
            if (callback) callback(response);
        },
        error: function(xhr, status, error) {
            if (callback) callback({
                success: false,
                message: 'AJAX error: ' + error
            });
        }
    });
}
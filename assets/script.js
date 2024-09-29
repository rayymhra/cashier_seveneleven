// show password
const passwordField = document.getElementById("password");
const showPasswordCheckbox = document.getElementById("show-password");

showPasswordCheckbox.addEventListener("change", function () {
  passwordField.type = this.checked ? "text" : "password";
});


// DASHBOARD
// Content for each section
const kelolaUserContent = `
<h3>Kelola User</h3>
<p>Here you can manage users. Add, edit, or delete users from this section.</p>
`;
const kelolaBarangContent = `
<h3>Kelola Barang</h3>
<p>Manage your inventory here. Add new products, update stock, or remove items.</p>
`;
const kelolaSupplierContent = `
<h3>Kelola Supplier</h3>
<p>Manage your suppliers in this section. Add new suppliers or update their details.</p>
`;

// Change content when clicking on sidebar links
document.getElementById('kelolaUserLink').addEventListener('click', function () {
document.getElementById('contentArea').innerHTML = kelolaUserContent;
});

document.getElementById('kelolaBarangLink').addEventListener('click', function () {
document.getElementById('contentArea').innerHTML = kelolaBarangContent;
});

document.getElementById('kelolaSupplierLink').addEventListener('click', function () {
document.getElementById('contentArea').innerHTML = kelolaSupplierContent;
});

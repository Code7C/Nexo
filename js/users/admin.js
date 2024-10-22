function openPopup(popupId) {
    document.getElementById(popupId).style.display = 'flex';
}

function closePopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
}

function confirmDelete(action) {
    return confirm("¿Estás seguro de que deseas eliminar este elemento?");
}
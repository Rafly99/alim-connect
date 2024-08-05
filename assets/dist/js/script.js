// Menyimpan posisi scroll sebelumnya

var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.getElementById("navbar").style.top = "0";
  } else {
    document.getElementById("navbar").style.top = "-50px";
  }
  prevScrollpos = currentScrollPos;
}
//   Notif Button
document.addEventListener('DOMContentLoaded', function() {
    var chatButton = document.getElementById('notifButton');
    var icon = chatButton.querySelector('i');

    chatButton.addEventListener('click', function() {
        if (icon.classList.contains('bi-chat')) {
            icon.classList.remove('bi-chat');
            icon.classList.add('bi-chat-fill');
        } else {
            icon.classList.remove('bi-chat-fill');
            icon.classList.add('bi-chat');
        }
    });
});

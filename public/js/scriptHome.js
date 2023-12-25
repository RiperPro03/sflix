function toggleLikeSerieAndRefresh(button) {
    const title = button.getAttribute('data-title');
    toggleLike_serie(title, button).then(() => {
        window.location.reload();
    });
}

function toggleLike_serie(title, button) {
    event.preventDefault();

    // Retourner la promesse de fetch pour chaîner les appels
    return fetch('/toggle-like/' + encodeURIComponent(title), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ title: title })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let heartIconPath = data.liked ? 'img-icons/heart-fill.svg' : 'img-icons/heart.svg';
                button.querySelector('.heart-icon').setAttribute('src', heartIconPath);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', function() {
        toggleLike_serie(this.dataset.title, this);
    });
});
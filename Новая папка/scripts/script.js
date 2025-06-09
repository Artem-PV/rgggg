const swiper = new Swiper('.love-us-slider', {
    slidesPerView: 3,
    spaceBetween: 0,
    initialSlide: 1,
    centeredSlides: true,
    loop: true,
    resistanceRatio: 0,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        1440: {
            slidesPerView: 3.7
        },
        393: {
            slidesPerView: 1
        },
        0: {
            slidesPerView: 1
        }
    }
});
window.addEventListener('resize', () => {
        swiper.update();
    }
);
function closeModal() {
    document.getElementById('login-modal').style.display = 'none';
    document.getElementById('signup-modal').style.display = 'none';
}

document.querySelector('.sign-in').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('login-modal').style.display = 'flex';
    document.getElementById('signup-modal').style.display = 'none';
});

document.querySelector('.sign-up').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('signup-modal').style.display = 'flex';
    document.getElementById('login-modal').style.display = 'none';
});

window.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeModal();
    }
});

document.getElementById('form-login').addEventListener('submit', submitForm);
document.getElementById('form-signup').addEventListener('submit', submitForm);

function submitForm(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Сохраняем в localStorage
            localStorage.setItem('user', JSON.stringify(data.user));
            alert('Успешно!');
            closeModal();
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch(err => {
        alert('Серверная ошибка.');
        console.error(err);
    });
}
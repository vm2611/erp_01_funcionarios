console.log("está funcionando");

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    const indicator = document.getElementById('indicator');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            // Remove a classe ativa de todos os links
            navLinks.forEach(function (link) {
                link.classList.remove('active');
            });

            // Adiciona a classe ativa apenas ao link clicado
            this.classList.add('active');

            // Move o indicador para o link clicado
            if (this !== indicator.parentNode) {
                this.appendChild(indicator);
            }
        });
    });
});

// Máscara para telefone
var behavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-0000';
};

var options = {
    onKeyPress: function (val, e, field, options) {
        field.mask(behavior.apply({}, arguments), options);
    }
};

// Aplicando máscara para telefone
$('.phone').mask(behavior, options);

// Máscara para salário
var olhaso = function (val) {
    return val.replace(/\D/g, '').length === 15 ? '00.000,00' : '00.000,00';
};

var olhala = {
    onKeyPress: function (val, e, field, options) {
        field.mask(olhaso.apply({}, arguments), options);
    }
};

// Aplicando máscara para salário
$('.salary').mask(olhaso, olhala);

console.log("está funcionando");

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    const indicator = document.getElementById('indicator');

    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        const indicator = document.getElementById('indicator');
    
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Remove a classe ativa de todos os links
                navLinks.forEach(function(link) {
                    link.classList.remove('active');
                });
                
                // Adiciona a classe ativa apenas ao link clicado
                this.classList.add('active');
    
            
    
                // Move o indicador para o link clicado
                this.appendChild(indicator);
                
            });
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
var salaryMask = function (val) {
    return '000.000.000,00';
};

var salaryOptions = {
    reverse: true, // Esta opção ajuda a formatar valores monetários corretamente
    onKeyPress: function (val, e, field, options) {
        field.mask(salaryMask.apply({}, arguments), options);
    }
};

// Aplicando máscara para salário
$('.salary').mask(salaryMask, salaryOptions);

// Máscara para valor total
var valorTotalMask = function (val) {
    return '000.000.000,00';
};

var valorTotalOptions = {
    reverse: true,
    onKeyPress: function (val, e, field, options) {
        field.mask(valorTotalMask.apply({}, arguments), options);
    }
};

// Aplicando máscara para valor total
$('#valor_total').mask(valorTotalMask, valorTotalOptions);
document.addEventListener("DOMContentLoaded", function() {
    // Selecionar todos os itens do menu
    const menuItems = document.querySelectorAll('.nav-item');
    
    // Iterar sobre os itens do menu e verificar qual está ativo
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            // Remover a classe 'active' de todos os itens
            menuItems.forEach(function(menu) {
                menu.classList.remove('active');
            });
            
            // Adicionar a classe 'active' ao item clicado
            item.classList.add('active');
            
            // Atualizar a posição do indicador
            updateIndicator(item);
        });
    });

    // Função para atualizar a posição do indicador
    function updateIndicator(activeItem) {
        const indicator = document.getElementById('indicator');
        const activeItemRect = activeItem.getBoundingClientRect();
        
        // Ajustar a posição do indicador
        indicator.style.left = `${activeItemRect.left}px`;
    }

    // Definir o item ativo com base na URL atual
    const currentPage = window.location.pathname.split('/').pop(); // Pega o nome do arquivo
    menuItems.forEach(function(item) {
        if (item.querySelector('a').href.includes(currentPage)) {
            item.classList.add('active');
            updateIndicator(item);
        }
    });
});

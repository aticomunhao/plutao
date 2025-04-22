//Quando eecutar a pagina usa o select2

$(document).ready(function () {
     //Inicialize o Select2 com o tema Bootstrap 5
    $('.select2').select2({
       theme: 'bootstrap-5'
    });

//     // Supondo que o array de rotas selecionadas esteja embutido no HTML, você precisará garantir que ele esteja disponível como variável JavaScript no DOM
//     let rotasSelecionadas = window.rotasSelecionadas || [];

//     // Itera sobre as rotas selecionadas e define como selecionado
//     $.each(rotasSelecionadas, function (index, value) {
//         $('#id' + value).prop('selected', true);
//         $('#id' + value).trigger('change');
//     });
 });

////script de estado com cidade

$(document).ready(function () {
    $('#cidade1, #cidade2, #setorid').select2({
        theme: 'bootstrap-5',
        width: '100%',
    });

    function populateCities(selectElement, stateValue) {
        $.ajax({
            type: "get",
            url: "/retorna-cidade-dados-residenciais/" + stateValue,
            dataType: "json",
            success: function (response) {
                selectElement.empty();
                $.each(response, function (indexInArray, item) {
                    selectElement.append('<option value="' + item.id_cidade + '">' + item.descricao + '</option>');
                });
            },
            error: function (xhr, status, error) {
                console.error("An error occurred:", error);
            }
        });
    }

    $('#uf1').change(function (e) {
        var stateValue = $(this).val();
        $('#cidade1').removeAttr('disabled');
        populateCities($('#cidade1'), stateValue);
    });

    $('#uf2').change(function (e) {
        var stateValue = $(this).val();
        $('#cidade2').removeAttr('disabled');
        populateCities($('#cidade2'), stateValue);
    });

    $('#idlimpar').click(function (e) {
        $('#idnome_completo').val("");
    });
});


//tooltip permitindo um modal junto com o tooltip no mesmo botão

import {Tooltip} from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl);
    });
});

//// script controle de vagas

$(document).ready(function () {
    //Inicializar a variável para manter o estado da tabela selecionada
    var tabelaSelecionada = "{{ $pesquisa }}";

    //Esconder a tabela que não está selecionada inicialmente
    if (tabelaSelecionada === 'cargo') {
        $("#setorTabela").hide();
    } else {
        $("#cargoTabela").hide();
    }

    //Esconder o select que não está selecionado inicialmente
    if (tabelaSelecionada === 'cargo') {
        $("#setorSelectContainer").hide();
    } else {
        $("#cargoSelectContainer").hide();
    }

    // Monitorar a mudança nos botões de rádio
    $("input[type='radio'][name='pesquisa']").change(function () {
        var pesquisaSelecionada = $(this).val();
        $("#tipoPesquisa").val(
            pesquisaSelecionada); // Atualizar o campo hidden com a opção selecionada

        // Esconder a tabela e o select que não estão selecionados
        if (pesquisaSelecionada === 'cargo') {
            $("#cargoTabela").show();
            $("#setorTabela").hide();
            $("#cargoSelectContainer").show();
            $("#setorSelectContainer").hide();
        } else if (pesquisaSelecionada === 'setor') {
            $("#setorTabela").show();
            $("#cargoTabela").hide();
            $("#setorSelectContainer").show();
            $("#cargoSelectContainer").hide();
        }
    });
});


//filtro do editar usuario
$(document).ready(function () {
    function removeAcentos(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }
    $("#nome_pesquisa").on("keyup", function () {
        var value = removeAcentos($(this).val());
        console.log(value);
        $("#myTable tr").filter(function () {
            var text = removeAcentos($(this).text());
            $(this).toggle(text.indexOf(value) > -1);
        });
    });
});

$(function(){
    // VARIÁVEIS GLOBAIS //
    var pathname = window.location.pathname;
    var rotina = pathname.replace(/^\//, '');

    // BOOTS //
    if(pathname !== '/dashboard' && pathname !== '/fluxo'){
        carregarTabela(0);
    }

    // FUNÇÕES GLOBAIS //
    
    $('body').on('focus', '#valor', function(){
        $(this).mask('#.##0,00', {reverse: true}).attr('maxlength', 11);
    });
    $('body').on('focus', '.maskDate', function(){
        $(this).mask('00/00/0000');
    });

    function stringParaData(dataString) {
        var partesData = dataString.split("/");
        return new Date(partesData[2], partesData[1] - 1, partesData[0]);
    }
    function obterDataHoje() {
        var hoje = new Date(); // Obtém a data de hoje
        hoje.setHours(0, 0, 0, 0); // Define horas, minutos, segundos e milissegundos para zero
        return hoje;
    }

    $('#mytable td').each(function(){
        
        var valor = parseFloat($(this).text());
        var dataString = $(this).closest('tr').find('#vencimento').text();
        var data1 = stringParaData(dataString);
        var hoje = obterDataHoje();
        
        if(valor < 0){
            $(this).css('color', 'red');
        }
        if(data1 < hoje){
            $(this).css('background', 'lightgreen');
        }
        if(data1.getTime() === hoje.getTime()){
            $(this).css('background','rgba(255, 255, 0, 0.5)');
        }
    });

    function carregarTabela(pagina){
        $('.card-header').html('<div class="text-center"><div class="spinner-border text-primary m-5" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only"></span></div></div>');
        $('#page').val(pagina);
        var dados = $('#formTable').serialize();
        $.ajax({
            url: pathname+"/lista",
            method: 'GET',
            data: dados
        }).done(function(data){
            $('.card-header').html(data);
        });
    }

    // FUNÇÕES ESPECÍFICAS //
    $('body').on('click', '.pagination a' , function(e){
        e.preventDefault();
        var pagina = $(this).attr('href').split('page=')[1];
        carregarTabela(pagina);
    });

    $('body').on('keyup submit' , '#formTable' , function(e){
        e.preventDefault();
        carregarTabela(0);
    });

    $('#orderBy, #sortBy, #regPag').change(function(){
        carregarTabela(0);
    });

    //$('#sortBy option:eq(0)').val('').text('Selecionar').prop('selected', true);;
    //$('#sortBy option:eq(1)').val('asc').text('▲');
    //$('#sortBy option:eq(2)').val('desc').text('▼');

    
    // DEMAIS //

    $('body').on('change', '#periodo', function(){
        $('#formFluxo').submit();
        return;
    });

    $('body').on('change', '#tipo', function(){
        var tipo = $(this).val();

        if(tipo === ""){
            $('#categoria').find("option").remove();
        }
        if(tipo != ""){
            $.get("/getCategoria/tipo=" + tipo, function (response) {
                $('#categoria').find("option").remove();
                $('#categoria').append(response);
            });
        }
    });

    $('body').on('click', '#btn-form', function(){

        var dadosFormulario = $('#formModal').serialize();
        
        $.ajax({
            type: 'POST',
            url: pathname,
            data: dadosFormulario,
            success: function(response){
                $('#modal_'+rotina).modal('hide');
                    resetForm();
                    carregarTabela();
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            },
        });
    });

    $('body').on('click', '#btnFormFluxoLancamento', function(){

        var dadosFormulario = $('#formModal').serialize();
        
        $.ajax({
            type: 'POST',
            url: '/lancamentosFluxo',
            data: dadosFormulario,
            success: function(response){
                $('#modal_lancamentos').modal('hide');
                    resetForm();
                    if(response == 'success'){
                        window.location.href = '/fluxo';
                    }
            },
        });
    });

    function resetForm(){
        $('#categoria').find("option").remove();
        $('#formModal').trigger('reset');
    }

    $('body').on('click', '#btn-create-lancamento', function(e){
        e.preventDefault();
        resetForm();
        $('#modal_lancamentos').modal('show');
    });

    $('body').on('click', '#btn-edit-lancamento', function(){
        var id = $(this).attr('data-id');
        var tipo = $(this).attr('data-tipo');
        
        $.get("/getCategoria/tipo=" + tipo, function (response){
            $('#categoria').find("option").remove();
            $('#categoria').append(response);

            $.get(pathname+"/id="+id, function(response){
                $('#formModal').trigger('reset');

                $.each(response, function(prefix, val){
                    $('input[name='+prefix+'], select[name='+prefix+'], textarea[name='+prefix+']').val(val);
                });
            $('#modal_lancamentos').modal('show');
            });
        });
    });

    $('body').on('click', '#btn-more-lancamento', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        if(confirm("Tem certeza que deseja CLONAR esse registro? ")) {
            $.get(pathname+"/clone/"+id, function(response){
                if(response === 'success'){
                    window.location.href = '/lancamentos';
                }
                return;
            });
        }
    });

    $('body').on('click', '#btn-delete-lancamento', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        if(confirm("Tem certeza que deseja EXCLUIR esse registro?")) {
            $.ajax({
                url: pathname+"/delete/"+id,
                method: 'GET',
                success: function(response){
                    if(response === 'success'){
                        window.location.href = '/lancamentos';
                    }
                }
            })
        }
    });

    $('body').on('click', '#btn-editar-fluxo', function(e){
        var vencimento = $(this).attr('data-vencimento');
        var valor = $(this).attr('data-valor');
        var categoria = $(this).attr('data-categoria');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var tipo = $(this).attr('data-tipo');
        
        if(categoria === 'Cartão de Crédito'){
            return;
        }
        $.get("/getCategoria/tipo=" + tipo, function (response) {
            $('#categoria').find("option").remove();
            $('#categoria').append(response);
        });

        $.ajax({
            type: 'POST',
            url: '/fluxoEdit',
            data: {
                'vencimento': vencimento,
                'valor': valor,
                'categoria': categoria,
                '_token': csrf_token
            },
            success: function(response){
                var id = response.id;
                $.get("/lancamentos/id="+id, function(response){
                    $.each(response, function(prefix, val){
                        $('input[name='+prefix+'], select[name='+prefix+'], textarea[name='+prefix+']').val(val);
                    });
                });
                $('#modal_lancamentos').modal('show');
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            },
        });
    });

    $('body').on('click', '#btn-view-cartao', function(){
        var pagamento = $(this).attr('data-pagamento');
        var vencimento = $(this).attr('data-vencimento');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: 'POST',
            url: '/cartao/view',
            data: {
                'pagamento': pagamento,
                'vencimento': vencimento,
                '_token': csrf_token,
            },
            success: function(response){
                $('#cartaoModal tbody').empty();

                $('#cartaoModalLabel').text(pagamento + ' | Vencimento: ' + vencimento);

                var totalNumerico = 0;

                $.each(response, function(index, record){
                    var newRow = '<tr>' +
                                    '<td>' + record.data + '</td>' +
                                    '<td>' + record.categoria + '</td>' +
                                    '<td>' + record.descricao + '</td>' +
                                    '<td>' + record.valor + '</td>' +
                                    '<td>' + record.credor + '</td>' +
                                    '<td class="text-center">' + record.parcela +' de '+ record.parcelaTotal +'</td>' +
                                '</tr>';
                    $('#cartaoModal tbody').append(newRow);

                    var valorFormatado = record.valor.replace(/\./g, '').replace(',', '.');
                    var valorNumerico = parseFloat(valorFormatado);
                    totalNumerico += valorNumerico;
                });
                var totalValor = totalNumerico.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                $('#cartaoModal tbody').append('<tr><td colspan="2"></td><td class="text-end"><strong>Total (R$):</strong></td><td><strong>' + totalValor + '</strong></td><td colspan="2"></td></tr>');
                $('#cartaoModal').modal('show');
            },
        });
    });

    $('body').on('click', '#linkDashboardCategoria', function(){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var categoria = $(this).attr('data-categoria');

        $.ajax({
            type: 'POST',
            url: '/dashboard/view/categoria',
            data: {
                'categoria': categoria,
                '_token': csrf_token,
            },
            success: function(response){
                $('#dashBoardModal tbody').empty();
                $('#dashBoardModalLabel').text(categoria);
                var totalNumerico = 0;
                $.each(response, function(index, record){
                    var newRow = '<tr>' +
                                    '<td>' + record.vencimento + '</td>' +
                                    '<td>' + record.categoria + '</td>' +
                                    '<td>' + record.descricao + '</td>' +
                                    '<td>' + record.valor + '</td>' +
                                    '<td>' + record.credor + '</td>' +
                                    '<td class="text-center">' + record.parcela +' de '+ record.parcelaTotal +'</td>' +
                                '</tr>';
                    $('#dashBoardModal tbody').append(newRow);
                    var valorFormatado = record.valor.replace(/\./g, '').replace(',', '.');
                    var valorNumerico = parseFloat(valorFormatado);
                    totalNumerico += valorNumerico;
                });
                var totalValor = totalNumerico.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                $('#dashBoardModal tbody').append('<tr><td colspan="2"></td><td class="text-end"><strong>Total (R$):</strong></td><td><strong>' + totalValor + '</strong></td><td colspan="2"></td></tr>');
                $('#dashBoardModal').modal('show');          
            },
        });
    });

    // FORM EDIT GLOBAL //
    $('body').on('click', '#btn-edit-'+rotina, function(){
        var id = $(this).attr('data-id');

        $('#formModal').trigger('reset');
        $.get(pathname+"/id="+id, function(response){
            $.each(response, function(prefix, val){
                $('input[name='+prefix+'], select[name='+prefix+'], textarea[name='+prefix+']').val(val);
            });
        });
        $('#modal_'+rotina).modal('show');
    });
});
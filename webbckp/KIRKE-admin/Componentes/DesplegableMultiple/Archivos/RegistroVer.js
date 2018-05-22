

$(function () {

    $(".cp_DesplegableMultiple").change(function () {

        var id_cp = $(this).attr("id_cp");
        var elemento = $(this).attr("elemento");
        var valor = $(this).val();

        fn_DesplegableMultiple(id_cp, elemento, valor);

        if ($(this).attr("elemento") == 0) {
            $('#cp_' + id_cp).val($(this).val());
        } else {
            $('#cp_' + id_cp).val('');
        }

    });

});

function fn_DesplegableMultiple(id_cp, elemento, valor) {

    var elemento_sig = elemento - 1;

    $.ajax({
        url: "index.php?kk_generar=3&componente=DesplegableMultiple&archivo=RegistroVer.php&id_cp=" + id_cp + "&elemento=" + elemento + "&valor=" + valor,
        async:false,
        success: function (data) {

            for (i = elemento_sig; i >= 0; i--) {
                $('#cp_' + id_cp + '_' + i).html('');
                $('#cp_div_' + id_cp + '_' + i).hide();
            }

            $('#cp_div_' + id_cp + '_' + elemento_sig).show();
            $('#cp_' + id_cp + '_' + elemento_sig).html(data);

        }
    });

}

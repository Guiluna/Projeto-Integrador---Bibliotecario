<?php
//cria o banco de dados se ele não existir
$db = new SQLite3('../../db/bibliotecario.db');
session_start(); 
$usuario_id = $_SESSION['usuarioId'];
$lista = $db->query("SELECT * FROM cad_escola WHERE id = '$usuario_id'  ");
$dados = $lista->fetchArray();
$nome_escola = $dados['nome'];           
?>
<style>
    input[type="radio"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  border: 2px solid #ccc;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  outline: none;
  cursor: pointer;
}

/* Estilo quando o input radio está marcado */
input[type="radio"]:checked {
  background-color: #8bc34a;
  border-color: #8bc34a;
}

/* Estilo do label do input radio */
.radio-label {
  display: inline-block;
  margin-left: 5px;
  font-size: 16px;
  color: #333;
}

/* Estilo do container que envolve o input radio e o label */
.radio-container {
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
}
/* estilo para a linha destacada */
.table-highlighted {
  background-color: #00FFFF;
}

</style>
<!-- Page-header start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-header-title">
                    <h5 class="m-b-10">Empréstimos de Acervo Bibliotecário</h5>
                    <p class="m-b-0">Cadastro de emprestimos e devoluções do acervo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="painel.php"> <i class="fa fa-home f-20"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)" class="lista">Emprestimos</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="pcoded-inner-content">
<div class="main-body">
<div class="page-wrapper">
<div class="page-body">
    <div class="card">
        <div class="card-block typography">
            <div class="tabela">

            </div>
            <div class="text-center">
                <center>
                    <button class="btn waves-effect waves-light btn-success salvar" style="width: 120px"><i class=""></i><br>Salvar</button>
                    <button class="btn waves-effect waves-light btn-default cancelar1" style="width: 120px"><i class="ti-arrow-left"></i><br>Retornar</button>
                </center>

            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalExemploTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExemploTitulo">Acervo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="corpo_modal"></div>
      
    </div>
  </div>
</div>

<script>
    function stringToDate(dateString) {
        const [day, month, year] = dateString.split('/');
        return new Date(year, month - 1, day);
      }


    $(function(){
        $('.tabela').load('emprestimos/tabela.php');
        $('.salvar').hide();
        $('.cancelar1').hide();
        
        $('.cancelar1').click(function() { 
            $(this).hide();
            $('.salvar').hide();
            $('.pesquisar').show();
            $('.novo').show();
            $('.corpo').load('emprestimos/painel.php')
           
           
        });

        $('.salvar').click(function() { 
             id_usuario = $('#nome_usuario').data('id');
             id_acervo = $('#titulo_acervo').data('id');
             data_atual = $('#data_atual').val();
             data_devolucao = $('#data_escolhida').val();
             hora_atual = $('#hora_emprestimo').val();
             const dataAtual = stringToDate($('#data_atual').val());
             const dataPosterior = stringToDate($('#data_escolhida').val());

             if (dataAtual > dataPosterior) {
                swal("Aviso!", 'A data atual não pode ser maior que a data posterior!', "warning");
                
            }else if(id_acervo == ''){
                swal("Aviso!", 'Selecione um acervo para emprétimo!', "warning");
            }else if(id_usuario == ''){
                swal("Aviso!", 'Selecione um usuário!', "warning");
            }else{
                $.ajax({
                    type: 'POST',
                    url: 'emprestimos/salva_emprestimo.php',
                    data: {'id_usuario':id_usuario,'id_acervo':id_acervo,'data_atual':data_atual,'data_devolucao':data_devolucao,'hora_atual':hora_atual },
                    //se tudo der certo o evento abaixo é disparado
                    success: function(data) {
                       if(data == 1){
                        $('.tabela').load('emprestimos/cad_emprestimo.php');
                        swal(
                                'Cadastrado!',
                                'Empréstimo realizado com sucesso!',
                                'success'
                            )
                       }else{
                        swal("Aviso!", data, "warning");
                       }

                }       
            })

            }
            
             
                
               
            
             
        });
    })
</script>
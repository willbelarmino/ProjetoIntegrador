@extends('template/layout')

@section('title')
    <title>Registro - MoneyCash</title>
@endsection

@section('link')
    <a href="{{route('login')}}">
        <i class="material-icons">lock</i> Login
    </a>
@endsection


@section('content')
    <div class="full-page register-page" filter-color="black" data-image="../img/register.jpeg">
        <div class="container">
            <div id="content-view" class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="card card-signup">
                        <h2 class="card-title text-center">Criar cadastro</h2>
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="card-content">
                                    <div class="info info-horizontal">
                                        <div class="icon icon-rose">
                                            <i class="material-icons">assignment</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Planejamento Financeiro</h4>
                                            <p class="description">
                                                Você pode planejar suas finanças através de metas de gastos por categoria, acompanhando a evolução das despesas durante o mês.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="info info-horizontal">
                                        <div class="icon icon-primary">
                                            <i class="material-icons">insert_chart</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Saiba para onde vai seu dinheiro</h4>
                                            <p class="description">
                                                Mais completo que planilhas financeiras, com o MoneyCash você poderá analisar seus dados com gráficos e relatórios.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="info info-horizontal">
                                        <div class="icon icon-info">
                                            <i class="material-icons">notifications</i>
                                        </div>
                                        <div class="description">
                                            <h4 class="info-title">Não esqueça seus prazos</h4>
                                            <p class="description">
                                                Com MoneyCash você receberá lembretes e notificações no sistema de suas despesas a vencer.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <form id ="cadastroForm" class="form" enctype="multipart/form-data" >
                                    <div class="card-content">

                                        <div class="footer text-center">
                                            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail img-circle">
                                                    <img title="A imagem deve ser no formato jpg, png ou gif e ser menor que 500Kb!" src="../img/placeholder.jpg" alt="...">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                                                <div>
                                                        <span class="btn btn-round btn-primary btn-file btn-xs">
                                                            <span class="fileinput-new">Adicionar Foto</span>
                                                            <span class="fileinput-exists">Alterar</span>
                                                            <input type="file" onChange="validationFile(this.form,this)" accept="image/*" id="avatar" name="avatar" />
                                                        </span>
                                                    <br />
                                                    <a class="btn btn-danger btn-round fileinput-exists btn-xs" data-dismiss="fileinput">Remover</a>
                                                </div>
                                                <label id="avatar-error"></label>
                                            </div>
                                        </div>


                                        <div class="input-group space-error">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">face</i>
                                                </span>
                                            <input type="text" title="O nome deve conter até 20 caracteres" class="form-control" name="nome" id="nome" placeholder="Nome" required="true">

                                        </div>

                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>
                                            <input type="text" class="form-control" email="true" required="true" name="email" id="email" placeholder="E-mail">
                                            <label id="email-error"></label>
                                        </div>

                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                                            <input type="password" placeholder="Senha" class="form-control" name="senha" id="senha" required="true" />
                                            <label id="senha-error"></label>
                                        </div>

                                        <!-- If you want to add a checkbox to this form, uncomment this code -->
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="termos" name="optionsCheckboxes"> <span style="font-size:10px">Eu aceito os</span>
                                                <a style="font-size:10px" data-toggle="modal" data-target="#loading" >Termos de Uso e Política de Privacidade</a>.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button  id="btnBeginNow" disabled type="submit" class="btn btn-primary btn-round">Começar agora</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('template/include/footer')
    </div>

    <!-- Loading Modal -->
    <div style="margin-top: 10%;" class="modal fade" data-backdrop="static" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLoading">
        <div class="modal-dialog">
            <div class="col-md-10 col-md-offset-5">
                <img src="../img/loading.gif" alt="..." style="width: 70px; height: 40px;">
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalTermos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Termos de uso</h4>
                </div>
                <div class="modal-body">

                    POLÍTICA DE PRIVACIDADE
                    Leia com bastante atenção as disposições abaixo, as quais esclarecem, de forma clara, objetiva e direta, as informações sobre coleta, uso, armazenamento, tratamento e armazenamento de dados durante a utilização do Aplicativo, procedimentos em relação aos quais você manifesta consentimento, livre e expresso e informado.
                    1 Quais informações coletamos e armazenamos?
                    O MoneyCash coleta e armazena os dados fornecidos espontaneamente pelo Usuário, durante a sua utilização do Aplicativo, como, por exemplo, as informações cadastrais fornecidas para criação da conta de acesso ao Aplicativo, bem como os dados inseridos durante a utilização do Aplicativo, pelo preenchimento dos formulários, inclusive despesas, receitas, informações financeiras e de crédito, sonhos, metas e orçamentos, a estes não se limitando.

                    O MoneyCash pode ainda coletar e armazenar dados coletados automaticamente pelo Aplicativo, independentemente do fornecimento pelo Usuário. A cada acesso são obtidas informações, tais como, mas não se limitando a, características do dispositivo de acesso, do navegador, número IP com informação de data e hora, origem do IP, funcionalidades acessadas, informações sobre cliques e preferência de navegação, geolocalização, entre outros. O MoneyCash também poderá utilizar outras tecnologias, como cookies, para coletar informações do Usuário e melhorar a experiência de navegação. Alguns desses recursos podem ser bloqueados, o que poderá obstar o funcionamento de algumas funcionalidades do Aplicativo.

                    Assim, o Usuário desde já se encontra ciente acerca das informações coletadas pelos Aplicativos e expressa consentimento livre, expresso e informado com relação a tais procedimentos.

                    As informações coletadas são tratadas pela MoneyCash como sigilosas, e qualquer funcionário ou prestador de serviços que entrar em contato com elas se comprometerá a não desvirtuar a sua utilização, bem como em não as usar de modo destoante do previsto nestes Termos. O MoneyCash emprega todos os esforços razoáveis de mercado para garantir a segurança de seus sistemas na guarda de referidos dados.


                    2 Como utilizamos as informações coletadas?
                    As informações coletadas serão utilizadas para o funcionamento normal do Aplicativo, mantê-lo e aprimorá-lo, bem como para facilitar a identificação do perfil e necessidades do Usuário, a fim de personalizar e aprimorar a oferta de produtos e serviços pela MoneyCash.

                    As informações constantes no Aplicativo poderão, de forma estatística e anonimizada (isto é, não individualizada), ser repassadas a terceiros, de forma gratuita, ou não, e utilizadas para fins publicitários, desde que seja garantindo que não será possível a identificação do Usuário por meio dos dados fornecidos, no que o Usuário, desde logo, dá o consentimento livre, expresso e informado para tanto, nos termos exigidos pela Lei Nº 12.965/2014, Marco Civil da Internet.

                    Qualquer informação coletada poderá ser excluída, quando deixar de ser necessária ou pertinente para a finalidade que justificou a sua coleta e tratamento, sendo certo que nenhum dado pessoal trafegará pelo Aplicativo ou será armazenado em qualquer local, físico ou remoto, após a sua exclusão. O Usuário poderá desinstalar o Aplicativo a qualquer momento ou deixar de utilizá-lo, portanto deve estar ciente de que, mesmo nestes últimos casos, a MoneyCash respeitará o prazo de armazenamento mínimo de informações determinado pela legislação brasileira.

                    3 Com quem compartilhamos as informações coletadas?
                    O MoneyCash poderá compartilhar os dados em referência com as demais empresas parceiras, no que o Usuário desde logo concorda expressamente, o que poderá ocorrer especialmente nas seguintes hipóteses: (i) caso a viabilização dos negócios e/ou serviços oferecidos pela MoneyCash dependa do repasse de dados a parceiros; (ii) para proteção dos interesses da MoneyCash em qualquer tipo de conflito, incluindo ações judiciais, como, mas não se limitando a, quando exista a necessidade de identificar ou revelar dados de Usuário que esteja utilizando o Aplicativo com propósitos ilícitos; (iii) no caso de transações e alterações societárias envolvendo a MoneyCash; e (iv) mediante ordem judicial ou pelo requerimento de autoridades administrativas que detenham competência legal para requisição.

                    4 Como armazenamos as informações coletadas?
                    São adotadas pela MoneyCash as seguintes precauções na guarda e no tratamento das informações dos Usuários: (a) utilizamos os métodos padrões e de mercado para anonimizar os dados coletados; (b) possuímos mecanismos de proteção contra acesso não autorizado aos nossos sistemas; (c) somente autorizamos o acesso de pessoas previamente estabelecidas aos locais onde armazenamos as informações; e (d) aqueles que entrarem em contato com as informações deverão se comprometer a manter sigilo absoluto. A quebra do sigilo acarretará responsabilidade civil e o responsável será processado nos moldes da legislação brasileira.

                    Tais precauções, no entanto, não garantem integralmente que todas as informações que trafegam no Aplicativo não sejam acessadas por terceiros mal intencionados, por meio de métodos desenvolvidos para obter informações de forma indevida. Em razão disso, a MoneyCash não se responsabiliza por acessos ilícitos, bem como por atos de terceiros que logrem êxito em coletar ou utilizar, por quaisquer meios, dados cadastrais e informações disponibilizadas no Aplicativo pelo Usuário.

                    Não necessariamente, as informações ficarão armazenadas em servidores localizados no Brasil, podendo ser encaminhadas para qualquer país em que a MoneyCash tiver servidores.

                    5 Exclusão das informações?
                    Você pode solicitar, a qualquer momento, a exclusão de todos os dados incluídos na MoneyCash, por meio do Aplicativo, por meio de uma das formas de contato indicadas nos presentes Termos. Com isso, você não mais terá acesso a nenhuma das funcionalidades do Aplicativo. Se você estiver usando modalidade paga do Aplicativo, após a solicitação de exclusão, para reativar a sua conta, será necessário novo pagamento.

                    Atuaremos para atender a sua solicitação no menor espaço de tempo possível, sendo certo que, após a exclusão, os dados não mais poderão ser recuperados. O Usuário deve estar ciente, entretanto, que as informações cuja lei obriga a guarda permanecerão armazenadas, pelo prazo mínimo disposto nas normas aplicáveis.

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">

        function validationFile(form, input) {
            file = $(input).val();
            extensoes_permitidas = new Array(".gif", ".jpg", ".png");
            if (file) {
                //recupera a extensão do arquivo
                extensao = (file.substring(file.lastIndexOf("."))).toLowerCase();
                // verifica se a extensão é permitida
                valid_file = false;
                for (var i = 0; i < extensoes_permitidas.length; i++) {
                    if (extensoes_permitidas[i] == extensao) {
                        valid_file = true;
                        break;
                    }
                }
                if (!valid_file) {
                    // se a extensao for invalida mostra mensagem de erro
                    $(input).val("");
                    $("#avatar-error").html("Formato inválido.");
                }else{
                    // verifica tamanho da imagem
                    var arquivo = input.files[0];
                    if (arquivo.size>500999) {
                        $(input).val("");
                        $("#avatar-error").html("Tamanho da imagem inválido.");
                    } else {
                        $("#avatar-error").html("");
                        return true;
                    }
                }
            }
            return false;
        }


        $(document).ready(function(){

            /* Habilitar o botão Começar apenas quando o checkBox estiver marcado*/
            $( ".check" ).click(function() {
                setInterval(function(){
                    if ($("#termos").is(':checked')==true) {
                        $("#btnBeginNow").removeAttr('disabled');
                    }else {
                        $("#btnBeginNow").attr('disabled','disabled');
                    }
                }, 300);
            });

            /* Regras de validação do formulário*/
            $("#cadastroForm" ).validate({
                messages: {
                    nome: {
                        required: "Campo de preenchimento obrigatório."
                    },
                    email: {
                        required: "Campo de preenchimento obrigatório.",
                        email: "Insira um e-mail válido."
                    },
                    senha: {
                        required: "Campo de preenchimento obrigatório."
                    }
                },
                errorPlacement: function(error, element) {
                    $(element).parent('div').addClass('has-error');
                    error.insertAfter(element);
                }
            });

            /* Submita o formualário via Ajax*/
            $( "#cadastroForm" ).submit(function( e ) {
                if ($("#cadastroForm" ).valid()) {
                    var formData = new FormData($("#cadastroForm")[0]);
                    $.ajax({
                        type: "POST",
                        url: '{{route('criar.cadastro')}}',
                        data: formData,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $("#loading").modal('toggle');
                        },
                        success: function (data) {
                            if (data.status == "success") {
                                window.location.href = "{{route('categorias')}}";
                            } else {
                                setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                                setTimeout(function(){ showErrorNotification(data.message); }, 2500);
                            }
                        },
                        error: function (request, status, error) {
                            setTimeout(function(){ $("#loading").modal('toggle'); }, 2000);
                            setTimeout(function(){ showErrorNotification(error); }, 2500);
                        }
                    });
                }
                e.preventDefault(); // avoid to execute the actual submit of the form.
            });
        });
    </script>
@endsection
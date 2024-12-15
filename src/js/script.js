var altura = window.innerHeight
var largura = window.innerWidth

function actualizar_login() {
    var xhr = new XMLHttpRequest();

    xhr.open('POST', '/include/login.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
      if (xhr.status !== 200) {
        alert(xhr.responseText)
      }
    };
    xhr.send();
}
setInterval(actualizar_login, 3000);
function publicar(param) {
    var div = document.querySelector(".conteiner_pbl");
    var btn = document.querySelector("#pbl_abrir");
    if (div.classList.contains('remover')) {
        div.classList.remove('remover')
    }else{
        div.classList.add('remover')
    }
    if (btn.innerHTML == "<button>fechar</button>") {
        btn.innerHTML = "<button>"+param+"</button>";
    }else{
        btn.innerHTML = "<button>fechar</button>"
    }
}

function comentar(id,tipo) 
{
    var texto = document.querySelector(".footer_chat textarea");
    if (texto.value.length <= 0) {
        return false
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/include/comentar.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json');
    var comentarios = document.querySelector('.comentarios');
    xhr.onload = function() {
      if (xhr.status === 200) {
        comentarios.innerHTML += xhr.responseText;
        texto.value = "";
        var div = document.querySelector(".corpo_diminuido");
        div.scrollTop = div.scrollHeight;
      }else{

      }
    };
    var data = {
        id: id,
        texto: texto.value,
        tipo: tipo
    }
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);
}

function enviar_mensagem(id_dest) {
    var texto = document.querySelector(".footer_chat textarea");
    if (texto.value.length <= 0) {
        return false
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', indereco+'include/enviar_mensagem.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json');
    var comentarios = document.querySelector('.corpo_diminuido .msg');
    xhr.onload = function() {
      if (xhr.status === 200) {
        comentarios.innerHTML += xhr.responseText;
        texto.value = "";
        var div = document.querySelector(".corpo_diminuido");
        div.scrollTop = div.scrollHeight;
      }else{return false}
    };
    var data = {
        id_dest: id_dest,
        texto: texto.value
    }
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);
}
function mostrar_lista_amigos(id_user) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', indereco+'include/pegar_lista_amigos.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json');
    var container = document.querySelector('.container_amigos');
    xhr.onload = function() {
      if (xhr.status === 200) {
        if(container.innerHTML == ""){
            container.innerHTML = xhr.responseText;
        }else{
            container.innerHTML = "";
        }
      }else{return false}
    };
    var data = {
        id_user: id_user,
        indereco: indereco
    }
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);
}

function mudar_mode_coder() {
    var corpo = document.querySelector("#codigo_insert .codigo");
    var texto = '<div class="texto">carregue aqui o seu documento ou uma pasta</div>';
    var upload = '<div class="conteiner_file_codigo">'+texto+'<input class="file_codigo" type="file" name="arquivo" accept="dir/*" multiple=""></div>'

    if (corpo.innerHTML == upload) {
        corpo.innerHTML = '<textarea name="code" id="code">//digite aqui o seu codigo</textarea>';
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            //keyMap: "sublime",
            autoCloseBrackets: true,
            matchBrackets: true,
            showCursorWhenSelecting: true,
            mode: {name: "javascript", globalVars: true}
        });
    }else{
        corpo.innerHTML = upload;
    }
}
function personalizar(params) {
    if (params != 2) {
        var div = document.querySelector(params);
        div.style.cursor ="hand"
    }
    if (params == 2) {
        if (div1 = document.querySelector(".div1_img")) {
            div1 = document.querySelector(".div1_img");
            div1.style.cursor ="hand"
            div2 = document.querySelector(".div2_img");
            div2.style.cursor ="hand"
        }else{
            div2 = document.querySelector(".div2_img");
            div2.style.cursor ="hand"
        }
    }
}
function aba_carregar_foto() {
    var div = document.querySelector('#alerta');
    if (div.classList.contains('remover')) {
        div.classList.remove('remover')
    }else{
        div.classList.add('remover')
    }
}
function aba_alert(clas) {
    var div = document.querySelector(clas);
    if (div.classList.contains('remover')) {
        div.classList.remove('remover')
    }else{
        div.classList.add('remover')
    }
}
function aba_comentar_code(clas) {
    var div = document.querySelector(clas);
    var btn = document.querySelector('.btn_abrir_area_cmt');
    if (div.classList.contains('remover')) {
        div.classList.remove('remover')
        btn.style.right = "380px";
        btn.innerText = ">";
    }else{
        div.classList.add('remover')
        btn.style.right = "5px";
        btn.innerText = "<";
    }
}
function abri_fecha(id) {
    var i=0,array = [
        "#segunda_nav",
        "#abrir_menu"
    ];
    var tamanho= array.length - 1
    while (i <= tamanho) {
        if (id != array[i] && array[i] != id) {
            var mode = document.querySelector(array[i]);
            try {
                if (!mode.classList.contains('remover')) {
                    mode.classList.add('remover')
                }
            } catch (error) {
                
            }
        }
        i = i + 1;
    } 
    var mode = document.querySelector(id);
    if (mode.classList.contains('remover')) {
        mode.classList.remove('remover')
    }else{
        mode.classList.add('remover');
    }
}
function reagir(id,tipo,para) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/algoritimos/reagir.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
      if (xhr.status === 200) {
        var div_reagir = document.querySelector('.reac'+tipo+id)
        var img = document.querySelector('.reac'+tipo+id+' img')
        if (img.classList.contains('add')) {
            div_reagir.classList.remove('reac_animacao');
            if (tipo == "pbl") {
                div_reagir.innerHTML = "<img src='"+indereco+"bibliotecas/bootstrap/icones/heart.svg'><span>"+xhr.responseText+"</span>"
            }else{
                div_reagir.innerHTML = "<img src='"+indereco+"bibliotecas/bootstrap/icones/star.svg'><span>"+xhr.responseText+"</span>"
            }
        }else{
            div_reagir.classList.add('reac_animacao');
            if (tipo == "pbl") {
                div_reagir.innerHTML = "<img class='add' src='"+indereco+"bibliotecas/bootstrap/icones/heart-fill.svg'><span>"+xhr.responseText+"</span>"
            }else{
                div_reagir.innerHTML = "<img class='add' src='"+indereco+"bibliotecas/bootstrap/icones/star-fill.svg'><span>"+xhr.responseText+"</span>"    
            }
            div_reagir.classList.remove('reac_animacao');
        }
      }
    };
    var data = {
      id: id,
      tipo: tipo,
      para:para
    };
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);
}
function escrever(div) {
    var div = document.querySelector(div);
    var texto = div.innerHTML;
    div.innerHTML = '';
    var i = 0;

    function escrever_proxima_letra() {
        if (i < texto.length) {
            div.innerHTML += texto.charAt(i);
            i++;
            setInterval(escrever_proxima_letra, 200);
        }
    }
}
function abrir_info_pbl(id_pbl) {
    var info_pbl = document.querySelector(".pbl_info"+id_pbl)

    if (info_pbl.classList.contains("remover")) {
        info_pbl.classList.remove("remover")
        return true;
    }
    info_pbl.classList.add("remover")
    return true;
}
function abrir_denuncia_pbl(id_pbl) {
    var pbl = document.querySelector(".pbl_denuncia")

    if (pbl.classList.contains("remover")) {
        pbl.classList.remove("remover")
        pbl.classList.add("pbl_"+id_pbl)

        id_pbl = document.querySelector("#id_pbl_da_denuncia").value = id_pbl

        return true;
    }

    pbl.classList.add("remover")
    pbl.classList.remove("pbl_"+id_pbl)
    return true;
}
function denunciar(tipo) {
    var indereco;
    var id_razao_denuncia = document.querySelector("input.razao").value
    var id = document.querySelector("input#id_pbl_da_denuncia").value

    if (!indereco) {
        indereco = "";
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', indereco+'include/denunciar.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
      if (xhr.status === 200) {
        alert(xhr.responseText);
        aba_carregar_foto()
      }
    };
    var data = {
      id: id,
      id_razao: id_razao_denuncia,
      tipo: tipo
    };
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);

}
function abrir_partilhar(tipo,id) {
    var pbl = document.querySelector(".pbl_partilhar")

    if (pbl.classList.contains("remover")) {
        pbl.classList.remove("remover")
        pbl.classList.add(tipo+"_"+id)

        document.querySelector("#tipo_da_partilha").value = tipo;
        document.querySelector("#id_pbl_da_partilha").value = id

        return true;
    }

    pbl.classList.add("remover")
    pbl.classList.remove(tipo+"_"+id)
    return true;
}
function partilhar() {
    var indereco;
    var tipo_de_partilha = document.querySelector("#tipo_da_partilha").value;
    var id = document.querySelector("input#id_pbl_da_partilha").value
    var descricao = document.querySelector(".descricao_partilha").value;

    if (!indereco) {
        indereco = "";
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', indereco+'include/partilhar.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
      if (xhr.status === 200) {
        aba_alert(".pbl_partilhar")
      }
    };
    
    // Crie um objeto com os dados a serem enviados
    var data = {
      id: id,
      descricao: descricao,
      tipo: tipo_de_partilha
    };
    
    // Converta o objeto em uma string JSON
    var jsonData = JSON.stringify(data);
    
    // Envie a requisição AJAX com os dados JSON
    xhr.send(jsonData);

}
function rolagem_automatica(para) {
    elemento = document.querySelector(para);
    elemento.scrollIntoView({behavior:'smooth'})
}
function abrir_storie(id_user) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', indereco+'include/visualizar_storie.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    var corpo = document.querySelector(".visualizar_storie .container");
    var corp = document.querySelector(".visualizar_storie");
    if (id_user == "remover") {
        if (!corp.classList.contains('remover')) {
            corp.classList.add('remover')
        } 
        corpo.innerHTML = "";
        return true;
    }
    xhr.onload = function() {
      if (xhr.status === 200) {
        if (corp.classList.contains('remover')) {
            corp.classList.remove('remover')
        }
        corpo.innerHTML = xhr.responseText
      }
    };
    var data = {
      id_user: id_user
    };
    var jsonData = JSON.stringify(data);
    xhr.send(jsonData);
}
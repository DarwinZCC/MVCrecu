<section class="container-m row px-4 py-4">
  <h1>{{FormTitle}}</h1>
</section>
<section class="container-m row px-4 py-4">
  <form action="index.php?controller=consultoria&action=run&mode={{mode}}&id={{asignacion.id}}" method="POST" class="col-12 col-m-8 offset-m-2">
    <div class="row my-2 align-center">
      <label class="col-12 col-m-3" for="idD">Código</label>
      <input class="col-12 col-m-9" readonly disabled type="text" name="idD" id="idD" placehoder="Código" value="{{asignacion.id}}" />
      <input type="hidden" name="mode" value="{{mode}}" />
      <input type="hidden" name="id" value="{{asignacion.id}}" />
      <input type="hidden" name="xssToken" value="{{xssToken}}" />
    </div>
    <div class="row my-2 align-center">
      <label class="col-12 col-m-3" for="nombre">Nombre</label>
      <input class="col-12 col-m-9" {{readonly}} type="text" name="nombre" id="nombre" placehoder="Nombre de la Asignación" value="{{asignacion.nombre}}" />
      {{if asignacion.nombre_error}}
      <div class="col-12 col-m-9 offset-m-3 error">
        {{asignacion.nombre_error}}
      </div>
      {{endif asignacion.nombre_error}}
    </div>
    <div class="row my-2 align-center">
      <label class="col-12 col-m-3" for="descripcion">Descripción</label>
      <textarea class="col-12 col-m-9" {{readonly}} name="descripcion" id="descripcion" placehoder="Descripción de la Asignación">{{asignacion.descripcion}}</textarea>
      {{if asignacion.descripcion_error}}
      <div class="col-12 col-m-9 offset-m-3 error">
        {{asignacion.descripcion_error}}
      </div>
      {{endif asignacion.descripcion_error}}
    </div>
    <div class="row my-4 align-center flex-end">
      {{if showCommitBtn}}
      <button class="primary col-12 col-m-2" type="submit" name="btnConfirmar">Confirmar</button>
      &nbsp;
      {{endif showCommitBtn}}
      <button class="col-12 col-m-2" type="button" id="btnCancelar">
        {{if showCommitBtn}}
        Cancelar
        {{endif showCommitBtn}}
        {{if not showCommitBtn}}
        Regresar
        {{endif not showCommitBtn}}
      </button>
    </div>
  </form>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const btnCancelar = document.getElementById("btnCancelar");
    btnCancelar.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.location.assign("index.php?controller=consultoria&action=run&mode=LST");
    });
  });
</script>
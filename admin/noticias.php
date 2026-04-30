<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim(mysqli_real_escape_string($conexion, $_POST['titulo'] ?? ''));
    $resumen = trim(mysqli_real_escape_string($conexion, $_POST['resumen'] ?? ''));
    $contenido = trim(mysqli_real_escape_string($conexion, $_POST['contenido'] ?? ''));
    $categoria = in_array($_POST['categoria'] ?? '', ['aviso','promocion','recomendacion','destino','comunicado'], true) ? $_POST['categoria'] : 'aviso';
    $estado = in_array($_POST['estado'] ?? '', ['borrador','publicado'], true) ? $_POST['estado'] : 'borrador';
    $fecha = !empty($_POST['fecha_publicacion']) ? "'" . mysqli_real_escape_string($conexion, $_POST['fecha_publicacion']) . "'" : 'NULL';

    $imagen = 'default.png';
    if (!empty($_FILES['imagen']['name'])) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'], true)) {
            $nombreImg = uniqid('noticia_') . '.' . $ext;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../assets/imagenes/' . $nombreImg)) {
                $imagen = $nombreImg;
            }
        }
    }
    $imagenEsc = mysqli_real_escape_string($conexion, $imagen);
    $categoriaEsc = mysqli_real_escape_string($conexion, $categoria);
    $estadoEsc = mysqli_real_escape_string($conexion, $estado);

    if ($titulo === '') {
        $_SESSION['flash'] = ['tipo'=>'danger','msg'=>'El titulo es obligatorio.'];
    } else {
        $sql = "INSERT INTO noticias (titulo, resumen, contenido, imagen, categoria, estado, fecha_publicacion)
                VALUES ('$titulo','$resumen','$contenido','$imagenEsc','$categoriaEsc','$estadoEsc',$fecha)";
        $_SESSION['flash'] = mysqli_query($conexion, $sql)
            ? ['tipo'=>'success','msg'=>'Noticia guardada.']
            : ['tipo'=>'danger','msg'=>'Error al guardar: ' . mysqli_error($conexion)];
    }
    header('Location: noticias.php');
    exit();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    if ($id > 0) {
        mysqli_query($conexion, "DELETE FROM noticias WHERE id=$id");
        $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Noticia eliminada.'];
    }
    header('Location: noticias.php');
    exit();
}

$noticias = mysqli_query($conexion, "SELECT * FROM noticias ORDER BY created_at DESC");
$tituloPagina = 'Noticias';
$paginaActual = 'noticias';
require_once 'includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Noticias</h4>
        <p class="text-muted small mb-0">Publica avisos, promociones y recomendaciones para el sitio.</p>
    </div>
    <a href="../componentes/noticias/noticias.php" class="btn btn-outline-primary" target="_blank">
        <i class="fa-solid fa-up-right-from-square me-1"></i>Ver pagina publica
    </a>
</div>

<?php if ($flash): ?>
<div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?> alert-dismissible show" data-auto-dismiss role="alert">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-12 col-lg-5">
        <form method="POST" enctype="multipart/form-data" class="card panel-card p-4">
            <h6 class="fw-bold mb-3">Nueva noticia</h6>
            <div class="mb-3">
                <label class="form-label">Titulo</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Resumen</label>
                <textarea name="resumen" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Contenido</label>
                <textarea name="contenido" class="form-control" rows="6"></textarea>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select">
                        <option value="aviso">Aviso</option>
                        <option value="promocion">Promocion</option>
                        <option value="recomendacion">Recomendacion</option>
                        <option value="destino">Destino</option>
                        <option value="comunicado">Comunicado</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="borrador">Borrador</option>
                        <option value="publicado">Publicado</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Fecha publicacion</label>
                    <input type="date" name="fecha_publicacion" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-6">
                    <label class="form-label">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                </div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">
                <i class="fa-solid fa-floppy-disk me-1"></i>Guardar noticia
            </button>
        </form>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card panel-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Categoria</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$noticias || mysqli_num_rows($noticias) === 0): ?>
                        <tr><td colspan="5" class="text-center text-muted py-5">Sin noticias registradas</td></tr>
                    <?php else: ?>
                        <?php while ($n = mysqli_fetch_assoc($noticias)): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($n['titulo']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($n['resumen'] ?? '') ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($n['categoria']) ?></span></td>
                            <td><span class="badge <?= $n['estado'] === 'publicado' ? 'badge-confirmada' : 'badge-inactivo' ?>"><?= htmlspecialchars($n['estado']) ?></span></td>
                            <td class="small text-muted"><?= $n['fecha_publicacion'] ? date('d/m/Y', strtotime($n['fecha_publicacion'])) : '-' ?></td>
                            <td class="text-end">
                                <a href="noticias.php?eliminar=<?= (int)$n['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Eliminar esta noticia?">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

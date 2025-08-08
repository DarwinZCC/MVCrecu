<?php

namespace App\Controllers;

use App\Dao\Consultoria;
use Exception;

const WORKWITH_URL = "/index.php?controller=consultoria&action=run&mode=LST";

class ConsultoriaController
{
    private array $viewData = [];
    private string $mode = "DSP";
    private array $modeDescriptions = [
        "DSP" => "Detalle de Asignación %s %s",
        "INS" => "Nueva Asignación",
        "UPD" => "Editar Asignación %s %s",
        "DEL" => "Eliminar Asignación %s %s",
        "LST" => "Listado de Asignaciones"
    ];
    private string $readonly = "";
    private bool $showCommitBtn = true;
    private array $asignacion = [
        "id" => 0,
        "nombre" => "",
        "descripcion" => ""
    ];
    private Consultoria $dao;

    public function __construct()
    {
        $this->dao = new Consultoria();
    }

    public function run(): void
    {
        try {
            $this->getData();
            if ($this->isPostBack()) {
                if ($this->validateData()) {
                    $this->handlePostAction();
                }
            }
            $this->setViewData();
            extract($this->viewData);
            require '../src/Views/consultorias/Consultoria.view.php';
        } catch (Exception $ex) {
            header("Location: " . WORKWITH_URL . "&message=" . urlencode($ex->getMessage()));
            exit;
        }
    }

    private function isPostBack(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    private function getData(): void
    {
        $this->mode = $_GET["mode"] ?? "LST";
        if (isset($this->modeDescriptions[$this->mode])) {
            $this->readonly = ($this->mode === "DEL" || $this->mode === "DSP") ? "readonly" : "";
            $this->showCommitBtn = $this->mode !== "DSP" && $this->mode !== "LST";

            if ($this->mode !== "INS" && $this->mode !== "LST") {
                $id = intval($_GET["id"] ?? 0);
                $this->asignacion = $this->dao::getById($id);
                if (!$this->asignacion) {
                    throw new Exception("No se encontró el registro", 1);
                }
            } elseif ($this->mode === "LST") {
                $this->viewData['asignaciones'] = $this->dao::getAll();
            }
        } else {
            throw new Exception("Modo inválido", 1);
        }
    }

    private function validateData(): bool
    {
        $errors = [];
        $this->asignacion["id"] = intval($_POST["id"] ?? 0);
        $this->asignacion["nombre"] = strval($_POST["nombre"] ?? "");
        $this->asignacion["descripcion"] = strval($_POST["descripcion"] ?? "");

        if (empty($this->asignacion["nombre"])) {
            $errors["nombre_error"] = "El nombre es requerido";
        }

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                $this->asignacion[$key] = $value;
            }
            return false;
        }
        return true;
    }

    private function handlePostAction(): void
    {
        switch ($this->mode) {
            case "INS":
                $this->handleInsert();
                break;
            case "UPD":
                $this->handleUpdate();
                break;
            case "DEL":
                $this->handleDelete();
                break;
            default:
                throw new Exception("Modo inválido", 1);
                break;
        }
    }

    private function handleInsert(): void
    {
        $result = $this->dao::insert(
            $this->asignacion["nombre"],
            $this->asignacion["descripcion"]
        );
        if ($result > 0) {
            header("Location: " . WORKWITH_URL . "&message=" . urlencode("Asignación creada exitosamente"));
            exit;
        }
    }

    private function handleUpdate(): void
    {
        $result = $this->dao::update(
            $this->asignacion["id"],
            $this->asignacion["nombre"],
            $this->asignacion["descripcion"]
        );
        if ($result > 0) {
            header("Location: " . WORKWITH_URL . "&message=" . urlencode("Asignación actualizada exitosamente"));
            exit;
        }
    }

    private function handleDelete(): void
    {
        $result = $this->dao::delete($this->asignacion["id"]);
        if ($result > 0) {
            header("Location: " . WORKWITH_URL . "&message=" . urlencode("Asignación eliminada exitosamente"));
            exit;
        }
    }

    private function setViewData(): void
    {
        $this->viewData["mode"] = $this->mode;
        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->asignacion["id"],
            $this->asignacion["nombre"]
        );
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["asignacion"] = $this->asignacion;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TrabajoCGRController extends Controller
{
    public function listadadoTrabajosIniciados(){
        $consulta = DB::connection('mysql2')->table('trabajos')->
        join('informes_areacgrs','informes_areacgrs.id','=','trabajos.id_informe')
            ->join('tipo_informe','trabajos.id_tipo_informe','=','tipo_informe.id_informe')
            ->where('ocupado','=',1)
            ->get();
            foreach ($consulta as $items){
                $actualizar ='<a class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary botonActualizar" data-idTabla="'.$items->numeroInforme.'"><span class="svg-icon svg-icon-2 svg-icon-primary ">' .
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>' .
                    '<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>' .
                    '</svg></span></a>';
                $continuar = '<a href="'.route("TrabajoIniciado",["numero" =>$items->numeroInforme,"informe" => $items->id]).'" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary botonContinuar"><span class="svg-icon svg-icon-2 svg-icon-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' .
                    '<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect><path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>' .
                    '</svg></span></a>';
                $tabla = [
                    'numero' =>$items->numeroInforme,
                    'tipoDoc' => $items->informe,
                    'plazo' => '',
                    'etapa' => $items->etapa_actual,
                    'estado' =>$items->estado,
                    'acciones' => '<td>'. $actualizar.$continuar.'</td>'
                ];
                $arreglo["data"][] =$tabla;
            }
            if (!isset($arreglo["data"])) {
                $arreglo = ["data" => "", "draw" => 1, "recordsFiltered" => 0, "recordsTotal" => 0];
            }
            return json_encode($arreglo);
    }
    public function informacionTrabajo(Request $request){
        $consulta = DB::connection('mysql2')->table('informes_areacgrs')
            ->join('tipo_informe','informes_areacgrs.tipo_informe','=','tipo_informe.id_informe')
            ->where('informes_areacgrs.id','=',$request->informe)->get();
        return $consulta;
    }

    public function AntecedentesPreliminares(Request $request){

        $consulta = DB::connection('mysql2')->table('informes_areacgrs')
            ->join('tipo_informe','informes_areacgrs.tipo_informe','=','tipo_informe.id_informe')
            ->join('informe_items','informes_areacgrs.id','=','informe_items.id_informe')
            ->where('informes_areacgrs.id','=',$request->informe)
            ->get();
        if(count($consulta) == 0){
            $clase ='oculta';
        }else{
            $clase ='';
        }
        $contenido='<div>
                        <div class="d-flex flex-stack flex-wrap row margenes">
                            <div class="col-4 ">
                                <a class="btn btn-primary" target="_blank" href="'.route('DescargarInforme').'">Descargar preinforme-informe etc</a>
                            </div>
                            <div class="col-4">
                                <select class="form-select"><option>Otros Documentos</option></select>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary">Revisar</button>
                            </div>
                        </div>
                        <div class="row margenes '.$clase.'">
                            <h4>Desglose Informe</h4>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu" id="justificacion">Justificación</button>
                                <button class="btn btn-primary btonesMenu AntGenerales">Antecedentes Generales</button>
                                <button class="btn btn-primary btonesMenu Objetivo">Objetivo</button>
                                <button class="btn btn-primary btonesMenu Metodologia">Metodología</button>
                                <button class="btn btn-primary btonesMenu Muestra">Objetivo y Muestra</button>
                            </div>
                        </div>
                        <div class="row margenes">
                            <h4>Resultado</h4>
                            <div class="d-flex flex-row-reverse">
                                <button class="btn btn-primary masItem">Agregar Otro <span class="fa fa-plus"></span></button>
                            </div>
                            <table class="table table-row-bordered gy-5" id="tablaResultado">
                                <thead>
                                    <th>Número</th>
                                    <th>Capitulo</th>
                                    <th>Titulo</th>
                                    <th>Estamento</th>
                                    <th>Acciones</th>
                                </thead>
                            </table>
                        </div>
                    </div>';
        return compact('contenido','consulta');
    }
    public function tablaResultado(Request $request){
        $consulta= DB::connection('mysql2')->table('informes_areacgrs')
            ->join('tipo_informe','informes_areacgrs.tipo_informe','=','tipo_informe.id_informe')
            ->join('informe_items','informes_areacgrs.id','=','informe_items.id_informe')
            ->join('resultado_informe','informe_items.id_informe_items','=','resultado_informe.id_informe_items')
            ->where('informes_areacgrs.id','=',$request->id_informe)
            ->get();

        foreach ($consulta as $items){
            $actualizar ='<a class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary botonActualizar" data-idTabla="'.$items->id_resultado.'"><span class="svg-icon svg-icon-2 svg-icon-primary ">' .
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>' .
                '<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>' .
                '</svg></span></a>';
            $continuar = '<a class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary botonContinuar" data-idTabla="'.$items->id_resultado.'"><span class="svg-icon svg-icon-2 svg-icon-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' .
                '<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect><path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>' .
                '</svg></span></a>';
            $tabla = [
                'numero' =>$items->numero,
                'capitulo' => $items->capitulo,
                'titulo' => $items->titulo,
                'estamento' => '<a class="btn btn-sm btn-primary me-2 botonAgregar" data-idTabla="'.$items->id_resultado.'">Agregar<span class="fa fa-plus"></span></a>',
                'acciones' =>'<td>'. $actualizar.$continuar.'</td>',
                'id_resultado' => $items->id_resultado
            ];
            $arreglo["data"][] =$tabla;
        }
        if (!isset($arreglo["data"])) {
            $arreglo = ["data" => "", "draw" => 1, "recordsFiltered" => 0, "recordsTotal" => 0];
        }
        return json_encode($arreglo);
    }
    public function DescargarInforme(){
        if(Auth::check()){
            $ruta = 'InformesCGR/AGUB-2022-06-EA11-1-10.xlsx';
            /* 2- AUDITOR; 3- ADMINISTRADOR; 4- JEFE DE EQUIPO; 6- JEFE DEPTO; 7- QA DEPTO; 8- QA GABINETE; 1- USUARIO CGR; 11- JEFE DE AREA */
             $archivo = storage_path('app/'.$ruta);
                if(file_exists($archivo)){
                    $nombreOriginal = basename($archivo);
                    return Response::file($archivo, ['Content-Disposition' => 'inline; filename="' . $nombreOriginal . '"']);
                }else{
                    return redirect()->route('Errores',['error' => 404]);// ERROR 404 NO EXISTE EL RECURSO
                }
        }else{
            return redirect()->route('Errores',['error' => 401]); /// ERROR 401, ES NECESARIO INICIAR SESIÓN PARA ACCEDER AL CONTENIDO.
        }
    }

    public function minutaYacta(){
        $contenido ='<div>
                        <div class="d-flex flex-stack flex-wrap row">
                            <h4>Minuta</h4>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu materia" >Materia Auditada</button>
                                <button class="btn btn-primary btonesMenu objetivo">Objetivo y Periodo Auditado</button>
                                <button class="btn btn-primary btonesMenu pInteres">Punto de interes</button>
                                <button class="btn btn-primary btonesMenu Acciones">Acciones a Desarrollar</button>
                            </div>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu btn-lg preMinuta">Descargar Pre-Minuta</button>
                                <button class="btn btn-primary btonesMenu btn-lg MinutaRevision">Subir para Revisión</button>
                            </div>
                        </div>
                        <div class="row margenes">
                            <h4>Acta</h4>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu btn-lg">Descargar Pre-Acta</button>
                                <button class="btn btn-primary btonesMenu btn-lg">Subir para Revisión</button>
                            </div>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu btn-lg">Subir Acta Firmada</button>
                            </div>
                        </div>
                        <div class="row margenes">
                            <h4>Oficio</h4>
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu btn-lg">Descargar Pre-Oficio</button>
                                <button class="btn btn-primary btonesMenu btn-lg">Subir para Revisión</button>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-primary">Finalizar Etapa</button>
                        </div>
                    </div>';
        return $contenido;
    }
    public function Oficio(){
        $contenido ='<div>
                        <div class=" d-flex flex-stack flex-wrap row">
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu" >Fechas de Prórrogas</button>
                                <button class="btn btn-primary btonesMenu ">Descargar Pre-Oficio</button>
                                <button class="btn btn-primary btonesMenu ">Subir para Revision</button>
                            </div>
                        </div>
                    </div>';
        return $contenido;
    }
    public function GenerarDescargo(){
        $contenido='<div>
                        <div class=" d-flex flex-stack flex-wrap row">
                            <div class="d-flex flex-center">
                                <button class="btn btn-primary btonesMenu" id="CarpetaDescargo">Iniciar Carpeta Descargo</button>
                            </div>
                            <div class="row margenes">
                                <h4>Estamentos Auditados</h4>
                                <table class="table table-row-bordered gy-5" id="Estamentos">
                                    <thead>
                                    <th>Estamento</th>
                                    <th>Personal a Cargo</th>
                                    <th>Cordinador</th>
                                    <th>Acciones</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';
        return $contenido;
    }

    public function RevisionDescargos(){
        session(['aud' => 'AreaCGR/']);
        $contenido='<div>
                        <div class="d-flex flex-stack flex-wrap" >
                            <div class="me-2">
                            </div>
                            <div class="row infoAdicional">
                                <div class="col-6">
                                    <div class="fw-bolde fs3">
                                        <button type="button" class="btn btn-primary menu-dropdown dropdown-toggle" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                            Retroalimentación
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true" >
                                            <div class="menu-item px-3">
                                                <a href="" class="menu-link px-3" >
                                                    Revisar Temporalidad
                                                </a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="" class="menu-link px-3" >
                                                  Solicitar Adicional
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-primary">Subir para Revisión</button>
                                </div>
                            </div>
                        </div>
                        <div class="margenizquierdo">
                            <iframe src="filemanager" class="filemanager" ></iframe>
                        </div>
                    </div>';
        return $contenido;
    }

    public function ActualizacionDescargo(){
        $contenido='<div>
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-primary">Agregar Otro <span class="fa fa-plus"></span></button>
                        </div>
                        <div class="row margenes">
                            <table class="table table-row-bordered gy-5" id="observaciones">
                                <thead>
                                    <th>Número</th>
                                    <th>Cápitulo</th>
                                    <th>Título</th>
                                    <th>Agregar Acciones</th>
                                    <th>ACCIONES</th>
                                </thead>
                            </table>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-primary">Enviar a Aprobación y Finalizar</button>
                        </div>
                    </div>';
        return $contenido;
    }

}

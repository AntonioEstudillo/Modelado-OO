<?php
namespace App\Http\Controllers;
use App\Demo\FlujoDemo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
final class DemoController extends Controller {
 public function index(){ return view('demo.index',['registros'=>session('registros',[]),'resultado'=>session('resultado')]); }
 public function ejecutar(Request $request){
  $datos=$request->validate(['nombre'=>'required|string|max:100','tipo'=>'required|in:general,pediatrico,oncologico','tipo_historial'=>'required|in:resumido,completo','diagnostico'=>'required|string|max:300','antecedentes'=>'nullable|string|max:300','medicamentos'=>'nullable|string|max:300','alergias'=>'nullable|string|max:300','estudios'=>'nullable|string|max:300']);
  $resultado=(new FlujoDemo())->ejecutar($datos); // BREAKPOINT 0
  $registros=session('registros',[]); $registros[]=$resultado['paciente']; session(['registros'=>$registros]);
  return redirect()->route('demo.index')->with('resultado',$resultado);
 }
 public function limpiar(){session()->forget('registros');return redirect()->route('demo.index');}
}

<?php

namespace App\Http\Controllers\Mantenimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{

    public function viewListMenu() {
        $menus = DB::table('tb_menu')->where('estatus', 1)->get();
        $submenus = DB::table('tb_submenu')->where('estatus', 1)->get();
        $result = [];

        foreach ($menus as $m) {
            $menu = [
                "text" => $m->descripcion,
                "icon" => $m->icon,
                "link" => $m->ruta,
                "submenu" => []
            ];
            if ($m->submenu) {
                foreach ($submenus as $sm) {
                    if ($m->id_menu == $sm->id_menu) {
                        array_push($menu["submenu"], [
                            "text" => $sm->descripcion,
                            "link" => $sm->ruta
                        ]);
                    }
                }
            }
            array_push($result, $menu);
        }

        return $result;
    }
    public function extractPermisos()
    {
        $menus = DB::table('tb_menu')->where('estatus', 1)->get();
        $submenus = DB::table('tb_submenu')->where('estatus', 1)->get();
        $result = [];

        foreach ($menus as $m) {
            $menu = [
                "id_m" => $m->id_menu,
                "text" => $m->descripcion,
                "icon" => $m->icon,
                "submenu" => []
            ];
            if ($m->submenu) {
                foreach ($submenus as $sm) {
                    if ($m->id_menu == $sm->id_menu) {
                        array_push($menu["submenu"], [
                            "id_sm" => $sm->id_submenu,
                            "text" => $sm->descripcion
                        ]);
                    }
                }
            }
            array_push($result, $menu);
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

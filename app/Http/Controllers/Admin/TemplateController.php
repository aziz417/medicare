<?php

namespace App\Http\Controllers\Admin;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use App\Helpers\AppMailMessage;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::paginate(15);
        return view('admin.templates.list', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['key' => Template::generateKey($request->name)]);
        $validated = $request->validate([
            "name" => "required|string",
            "subject" => "required_if:type,email|string",
            "key" => "required|string|unique:templates",
            "type" => "required|string",
            "content" => "required|string",
            "action" => "nullable|array",
            "after" => "nullable|string"
        ]);
        if( empty($validated['action']['path']??null) ){
            $validated['action'] = null;
        }
        $item = Template::create($validated);
        if( $item ){
            return back()->withSuccess("Template added successfully!");
        }
        return back()->withWarning("Failed to add Template!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        if( request()->has('show') ){
            $markdown = new AppMailMessage($template, auth()->user());
            $preview = $markdown->render("notifications::email", compact('template'));
            app('debugbar')->disable();
            return $preview;
        }
        return view('admin.templates.view', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        return view('admin.templates.create', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "subject" => "required_if:type,email|string",
            "content" => "required|string",
            "action" => "nullable|array",
            "after" => "nullable|string"
        ]);
        if( empty($validated['action']['path']??null) ){
            $validated['action'] = null;
        }
        
        $template->fill($validated);
        if( $template->save() ){
            return back()->withSuccess("Template updated successfully!");
        }
        return back()->withWarning("Failed to update Template!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        if( $template->removable && $template->delete() ){
            return back()->withSuccess("Template Deleted successfully!");
        }
        return back()->withWarning("Failed to delete Template!");
    }
}

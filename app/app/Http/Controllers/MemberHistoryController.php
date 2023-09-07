<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberHistoryRequest;
use App\Http\Requests\UpdateMemberHistoryRequest;
use App\Models\MemberHistory;

class MemberHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMemberHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemberHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemberHistory  $memberHistory
     * @return \Illuminate\Http\Response
     */
    public function show(MemberHistory $memberHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemberHistory  $memberHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberHistory $memberHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMemberHistoryRequest  $request
     * @param  \App\Models\MemberHistory  $memberHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberHistoryRequest $request, MemberHistory $memberHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemberHistory  $memberHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberHistory $memberHistory)
    {
        //
    }
}

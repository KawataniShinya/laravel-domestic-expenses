<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberCategoryHistoryRequest;
use App\Http\Requests\UpdateMemberCategoryHistoryRequest;
use App\Models\MemberCategoryHistory;

class MemberCategoryHistoryController extends Controller
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
     * @param  \App\Http\Requests\StoreMemberCategoryHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemberCategoryHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemberCategoryHistory  $memberCategoryHistory
     * @return \Illuminate\Http\Response
     */
    public function show(MemberCategoryHistory $memberCategoryHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemberCategoryHistory  $memberCategoryHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberCategoryHistory $memberCategoryHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMemberCategoryHistoryRequest  $request
     * @param  \App\Models\MemberCategoryHistory  $memberCategoryHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberCategoryHistoryRequest $request, MemberCategoryHistory $memberCategoryHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemberCategoryHistory  $memberCategoryHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberCategoryHistory $memberCategoryHistory)
    {
        //
    }
}

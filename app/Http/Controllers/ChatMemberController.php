<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatMemberRequest;
use App\Http\Requests\UpdateChatMemberRequest;
use App\Models\ChatMember;

class ChatMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatMemberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatMember $chatMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatMemberRequest $request, ChatMember $chatMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMember $chatMember)
    {
        //
    }
}

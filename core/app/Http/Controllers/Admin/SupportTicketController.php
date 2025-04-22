<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\SupportTicketManager;

class SupportTicketController extends Controller
{
    private $pageTitle;
    use SupportTicketManager;

    public function __construct()
    {
        parent::__construct();
        $this->userType = 'admin';
        $this->column = 'admin_id';
        $this->user = auth()->guard('admin')->user();
    }

    public function tickets()
    {
        $this->pageTitle = 'All Support Tickets';
        return $this->supportTicketData();
    }

    public function pendingTicket()
    {
        $this->pageTitle = 'Pending Tickets';
        return $this->supportTicketData('pending');
    }

    public function closedTicket()
    {
        $this->pageTitle = 'Closed Tickets';
        return $this->supportTicketData('closed');
    }

    public function answeredTicket()
    {
        $this->pageTitle = 'Answered Tickets';
        return $this->supportTicketData('answered');
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket','admin','attachments')->where('support_ticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }


    private function supportTicketData($scope = null){
        $accountNumberField  = 'CASE WHEN support_tickets.user_id != 0 THEN users.account_number ELSE "N/A" END';
        $items = SupportTicket::selectRaw('
            support_tickets.*,
            '.$accountNumberField.' AS account_number
        ');

        if($scope){
            $items->$scope();
        }

        $items = $items->leftJoin('users', 'support_tickets.user_id', '=', 'users.id')
        ->searchable(['name','subject','ticket', 'account_number'])
        ->filterable()
        ->orderable()
        ->dynamicPaginate();

        $pageTitle = $this->pageTitle;
        return view('admin.support.tickets', compact('items', 'pageTitle'));
    }


    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path = getFilePath('ticket');
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path.'/'.$attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);

    }

}

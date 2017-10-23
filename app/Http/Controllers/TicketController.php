<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Repositories\TicketRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\PriorityRepository;
use App\Repositories\CategoryRepository;

class TicketController extends Controller
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $departmentRepository;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $priorityRepository;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $categoryRepository;

    /**
     * Undocumented function
     *
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        TicketRepository $ticketRepository,
        DepartmentRepository $departmentRepository,
        PriorityRepository $priorityRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->middleware('auth')->except(['report', 'storeReport']);

        $this->ticketRepository     = $ticketRepository;
        $this->departmentRepository = $departmentRepository;
        $this->priorityRepository   = $priorityRepository;
        $this->categoryRepository   = $categoryRepository;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        $data = $this->ticketRepository->getAll();

        return view("modules.ticket.index", compact('data'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function archive()
    {
        $data = $this->ticketRepository->getAllArchived();

        return view("modules.ticket.archive", compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archivePost($id)
    {
        try {
            $this->ticketRepository->delete($id);

            $notice = [
                'status' => 'success',
                'message' => trans("notice.ticket.success", ['action' => 'arquivado'])
            ];
        } catch (\Illuminate\Database\QueryException $exception) {
            $notice = [
                'status' => 'danger',
                'message' => trans("notice.ticket.error", ['action' => 'arquivar'])
            ];
        }

        return redirect()->route("ticket.index")->with(compact('notice'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->ticketRepository->forceDelete($id);

            $notice = [
                'status' => 'success',
                'message' => trans("notice.ticket.success", ['action' => 'excluído'])
            ];
        } catch (\Illuminate\Database\QueryException $exception) {
            $notice = [
                'status' => 'danger',
                'message' => trans("notice.ticket.error", ['action' => 'excluir'])
            ];
        }

        return redirect()->route("ticket.archive")->with(compact('notice'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            $this->ticketRepository->restore($id);

            $notice = [
                'status' => 'success',
                'message' => trans("notice.ticket.success", ['action' => 'restaurado'])
            ];
        } catch (\Illuminate\Database\QueryException $exception) {
            $notice = [
                'status' => 'danger',
                'message' => trans("notice.ticket.error", ['action' => 'restaurar'])
            ];
        }

        return redirect()->route("ticket.archive")->with(compact('notice'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function report()
    {
        $departments = $this->departmentRepository->getForSelect(['id', 'title']);
        $priorities  = $this->priorityRepository->getForSelect(['id', 'title']);
        $categories  = $this->categoryRepository->getForSelect(['id', 'title']);

        return view('modules.ticket.report')
            ->with('departments', $departments)
            ->with('priorities', $priorities)
            ->with('categories', $categories);
    }

    /**
     * Undocumented function
     *
     * @param TicketRequest $request
     * @return void
     */
    public function storeReport(TicketRequest $request)
    {
        try {
            $request->merge(['situation' => 'open']);
            $data = $request->except('_token');

            array_map(function($value, $key) use (&$data) {
                switch ($key) {
                    case 'department':
                    case 'category':
                    case 'priority':
                        $data[$key . '_id'] = $value;
                        unset($data[$key]);
                        break;
                    default:
                        $data[$key] = $value;
                        break;
                }
            }, $data, array_keys($data));

            $ticket = $this->ticketRepository->store($data);

            $notice = [
                'status' => 'success',
                'message' => trans("notice.ticket.success", ['action' => 'cadastrado'])
            ];
        } catch (\Illuminate\Database\QueryException $exception) {
            $notice = [
                'status' => 'danger',
                'message' => trans("notice.ticket.error", ['action' => 'cadastrar'])
            ];
        }

        return redirect()->back()->with(compact('notice'));
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}

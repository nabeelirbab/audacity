<?php

namespace App\Admin\Controllers;

use App\ManagerTemplateMailable;
use App\ManagerMailer;
use App\Models\ManagerMailTemplate;
use Carbon\Carbon;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends AdminController
{
    protected function grid()
    {
        return new Grid(new ManagerMailTemplate(), function (Grid $grid) {
            $grid->model()->whereManagerId(Admin::user()->id);

            $grid->id();

            $grid->mailable();
            $grid->subject();

            $grid->column('updated_at')->dateHuman();

            $grid->disableRefreshButton();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableBatchActions();
            $grid->disableViewButton();
            $grid->disableDeleteButton();
        });
    }

    protected function form()
    {
        return new Form(new ManagerMailTemplate(), function (Form $form) {

            $form->display('id');
            $form->hidden('manager_id')->value(Admin::user()->id);
            $form->hidden('mailable');

            $form->text('subject')->required();
            /** @var ManagerTemplateMailable $mailable */
            $mailable = $form->model()->mailable;
            $macroses = Arr::join($mailable::getVariables(), ', ');

            $form->editor('html_template')->required()->help(___('help_macroses', ['list' => $macroses ]));
            $form->button(__('admin.send_test_email'))->on('click', $this->formatClick($form));

            $form->textarea('text_template');

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableViewCheck();
            $form->disableDeleteButton();
            $form->disableViewButton();

        });
    }

    public function test(Request $request)
    {

        $info = $request->only('template', 'subject', 'mailable');
        $rules = [
            'subject' => 'required',
            'template' => 'required',
            'mailable' => 'required'
        ];
        $validator = Validator::make($info, $rules);
        if ($validator->fails()) {

            $data = array();
            foreach($validator->messages()->getMessages() as $key =>  $messages) {
                $data[] = $messages[0];
            }
            return response()->json(['status' => false, 'message' => implode("\r\n",$data)]);
        }

        $subject = $request['subject'];
        $template = $request['template'];
        $mailable = $request['mailable'];

        try {

            /** @var ManagerTemplateMailable $mailable */
            $mail = $mailable::makeWithFakeData($subject, $template, Admin::user()->id);

            ManagerMailer::handle($mail, Admin::user()->email);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'successfully sent']);
    }

    private function formatClick(Form $form) {
        $path = $form->isCreating() ? $form->resource(-1) : $form->resource(-2);
        admin_toastr();

        return "
        var form = $(this).closest('form');
        var mailable = form.find('input[name=\"mailable\"]').val();
        var subject = form.find('input[name=\"subject\"]').val();
        var template = form.find('textarea[name=\"html_template\"]').val();
        var token = form.find('input[name=\"_token\"]').val();

        $.ajax({
            url: \"{$path}/test\",
            type: \"POST\",
            data: {mailable: mailable, subject : subject, template : template, _token: token},
            success: function(response, newValue){
                if (response.status){
                    toastr.success(response.message, '', {positionClass:\"toast-top-center\"});
                } else {
                    toastr.error(response.message, '', {positionClass:\"toast-top-center\"});
                }
            }
        });
        ";
    }
}

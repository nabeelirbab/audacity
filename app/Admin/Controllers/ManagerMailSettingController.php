<?php

namespace App\Admin\Controllers;

use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\ManagerMailSetting;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagerMailSettingController extends AdminController
{
    protected $translation = 'mail-setting';
    public function index(Content $content)
    {
        $content->translation($this->translation);
        $content->title($this->title());

        if(ManagerMailSetting::whereManagerId(Admin::user()->id)->exists()) {
            $content->body($this->form()->edit(Admin::user()->id));
        } else {
            $content->body($this->form());
        }

        return $content;
    }

    public function store() {
        if( ManagerMailSetting::whereManagerId(Admin::user()->id)->exists() )
            return $this->form()->update(Admin::user()->id);
        else
            return $this->form()->store();
    }

    protected function form()
    {
        $form = new Form(new ManagerMailSetting());

        $form->action(request()->fullUrl().'/store');

        $form->hidden('manager_id')->value(Admin::user()->id);
        $drivers = ['smtp'=>'smtp', 'sendmail' => 'sendmail', 'mailgun'=>'mailgun'];
        $ecnryptions = ['tls'=>'tls', 'ssl'=>'ssl'];

        $form->select('transport', 'Transport')->options($drivers)->required();
        $form->text('host', 'SMTP Host')->required();
        $form->number('port', 'SMTP Port')->required();
        $form->select('encryption', 'Encryption')->options($ecnryptions)->required();
        $form->text('username', 'SMTP User')->required();
        $form->text('password', 'SMTP Password')->required();
        $form->email('from_email', 'Send From')->help('If empty SMTP User will be used');
        $form->text('from_name', 'From Name')->required();
        $form->editor('main_template', 'Base Layout');

        $form->button(__('admin.send_test_email'))->on('click', $this->formatClick($form));

        $form->action(admin_url('email-settings'));

        $form->tools(
            function (Form\Tools $tools) {
                $tools->disableList();
                $tools->disableDelete();
                $tools->disableView();
            }
        );

        $form->saved(function (Form $form) {
            return $form->response()->success(__('admin.update_succeeded'))->redirect($form->resource(0));
        });

        return $form;
    }

    public function test(Request $request)
    {

        $info = $request->only('transport', 'host','port', 'encryption',
                                'username', 'password', 'from_name', 'from_email', 'main_template');
        $rules = [
            'transport'  => 'required',
            'host' => 'required',
            'port' => 'required',
            'encryption' => 'required',
            'username' => 'required',
            'password' => 'required',
            'from_name' => 'required'
        ];
        $validator = Validator::make($info, $rules);
        if ($validator->fails()) {

            $data = array();
            foreach($validator->messages()->getMessages() as $key =>  $messages) {
                $data[] = $messages[0];
            }
            return response()->json(['status' => false, 'message' => implode("\r\n",$data)]);
        }

        try {

            if(empty($info['from_email']))
                $info['from_email'] = $info['username'];

            $mail = ManagerTemplateMailable::makeWithFakeData('Test Subject', 'Test Body', Admin::id());

            ManagerMailer::handle($mail, Admin::user()->email);

            if(!empty($request['main_template']))
                $mail->setLayout($request['main_template']);

            $mailer = app()->makeWith('custom.mailer', $info);

            $mailer->to(Admin::user()->email)->send($mail);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['status' => true, 'message' => 'successfully sent']);
    }

    private function formatClick(Form $form) {
        $path = '/email-settings/test';

        return <<<JS
        var form = $(this).closest('form');

        var transport = form.find('[name=transport]').val();
        var host = form.find('[name=host]').val();
        var port = form.find('[name=port]').val();
        var encryption = form.find('[name=encryption]').val();
        var username = form.find('[name=username]').val();
        var password = form.find('[name=password]').val();
        var from_email = form.find('[name=from_email]').val();
        var from_name = form.find('[name=from_name]').val();
        var main_template = form.find('[name=main_template]').val();

        transport = 'smtp';
        encryption = 'tls';

        $.ajax({
            url: "{$path}",
            type: "POST",
            data: {transport: transport, host : host, port : port, encryption: encryption,
                   username: username, password: password, from_email: from_email,
                   from_name: from_name, main_template: main_template},
            success: function(response, newValue){
                if (response.status){
                    toastr.success(response.message, '', {positionClass:"toast-top-center"});
                } else {
                    toastr.error(response.message, '', {positionClass:"toast-top-center"});
                }
            }
        });
        JS;
    }
}
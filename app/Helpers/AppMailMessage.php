<?php 
namespace App\Helpers;

use App\Models\Template;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * AppMailMessage
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class AppMailMessage extends MailMessage
{
    /**
     * The Markdown template to render (if applicable).
     *
     * @var string|null
     */
    public $markdown = 'notifications::email';

    /**
     * The Database Template
     *
     * @var Template
     */
    public $template;

    /**
     * The Notifiable User
     *
     * @var User
     */
    public $notifiable;
    
    /**
     * The additional short-codes
     *
     * @var array
     */
    public $additionalShortCode;

    function __construct(Template $template = null, $notifiable = null, $additionalShortCode = [])
    {
        $this->notifiable = $notifiable;
        $this->template = $template;
        $this->additionalShortCode = $additionalShortCode;
        $this->configureTemplateContent();
        // Set Subject
        $subject = optional($this->template)->replaceShortCode(
            $this->template->subject??"", $this->additionalShortCode, $this->notifiable
        );
        $this->subject($subject ?? "New Message");
    }

    public function configureTemplateContent()
    {
        $this->serializeIntroLines();
        $this->setAction();
        $this->serializeOutroLines();

        // add view data
        $this->mergeViewData([
            'template' => $this->template,
        ]);
        
        return $this;
    }

    public function serializeIntroLines()
    {
        $content = optional($this->template)->replaceShortCode(
            $this->template->content??'', $this->additionalShortCode, $this->notifiable
        );
        $introLines = explode("\n", $content);
        foreach ($introLines as $line) {
            $this->line($line);
        }
    }

    public function serializeOutroLines()
    {
        $content = optional($this->template)->replaceShortCode(
            $this->template->after??"", $this->additionalShortCode, $this->notifiable
        );
        $outroLines = explode("\n", $content);
        foreach ($outroLines as $line) {
            $this->line($line);
        }
        return $this;
    }

    public function setAction()
    {
        $path = $this->template->action['path'] ?? false;
        if( $path ){
            $originalRouteName = substr($path, strlen('route:'));
            if( str()->startsWith($path, 'route:') && has_route($originalRouteName)){
                $path = route($originalRouteName);
            }
            $this->action($this->template->action['title']??'Click Here', $path);
        }
    }

    private function mergeViewData($data = [])
    {
        $this->viewData = array_merge($this->viewData, $data);
        return $this;
    }
}
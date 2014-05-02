<?php

namespace Pagekit\Comment\Event;

use Pagekit\Comment\Model\CommentInterface;
use Pagekit\Framework\Event\Event;

class MarkSpamEvent extends SpamEvent
{
    /**
     * @var mixed
     */
    protected $previousStatus;

    /**
     * Constructor.
     *
     * @param CommentInterface $comment
     * @param mixed            $previousStatus
     */
    public function __construct($comment, $previousStatus)
    {
        parent::__construct($comment);
        $this->previousStatus = $previousStatus;
    }

    /**
     * @return mixed
     */
    public function getPreviousStatus()
    {
        return $this->previousStatus;
    }
}
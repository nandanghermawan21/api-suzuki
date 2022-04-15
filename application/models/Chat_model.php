<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="ChatModel")
 */
class Chat_model extends CI_Model
{
    private $tableName = "chat";

    /**
     * @OA\Property()
     * @var int
     */
    public $id;
    private function idField(): string
    {
        return "id";
    }
    public function idJsonKey(): string
    {
        return "id";
    }

    /**
     * @OA\Property()
     * @var DateTime
     */
    public $createDate;
    private function createDateField(): string
    {
        return "create_date";
    }
    public function createDateJsonKey(): string
    {
        return "createDate";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $messageType;
    private function messageTypeField(): string
    {
        return "message_type";
    }
    public function messageTypeJsonKey(): string
    {
        return "messageType";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $sender;
    private function senderField(): string
    {
        return "sender";
    }
    public function senderJsonKey(): string
    {
        return "sender";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $receiver;
    private function receiverField(): string
    {
        return "receiver";
    }
    public function receiverJsonKey(): string
    {
        return "receiver";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $message;
    private function messageField(): string
    {
        return "message";
    }
    public function messageJsonKey(): string
    {
        return "message";
    }

    public function  fromJson($json): Chat_model
    {
        $data = new Chat_model();

        if (isset($json[$this->idJsonKey()])) {
            $data->id = $json[$this->idJsonKey()];
        }
        if (isset($json[$this->messageTypeJsonKey()])) {
            $data->messageType = $json[$this->messageTypeJsonKey()];
        }
        if (isset($json[$this->createDateJsonKey()])) { //DateTime::ATOM
            $data->createDate =  date_create(date(DATE_ATOM, strtotime($json[$this->createDateJsonKey()])));  //  DateTime::createFromFormat(DateTime::ISO8601, $json[$this->createDateJsonKey()]);
        }
        if (isset($json[$this->senderJsonKey()])) {
            $data->sender = $json[$this->senderJsonKey()];
        }
        if (isset($json[$this->receiverJsonKey()])) {
            $data->receiver = $json[$this->receiverJsonKey()];
        }
        if (isset($json[$this->messageJsonKey()])) {
            $data->message = $json[$this->messageJsonKey()];
        }

        return $data;
    }

    public function fromRow($row): Chat_model
    {
        $data = new Chat_model();
        $data->id = $row->{$this->idField()};
        $data->messageType = $row->{$this->messageTypeField()};
        $data->createDate = date_create(date(DATE_ATOM, strtotime($row->{$this->createDateField()})));
        $data->sender = $row->{$this->senderField()};
        $data->receiver = $row->{$this->receiverField()};
        $data->message = $row->{$this->messageField()};

        return $data;
    }

    public function toArray(): array
    {

        $data = array(
            $this->idField() => $this->id,
            $this->messageTypeField() => $this->messageType,
            $this->createDateField() => $this->createDate->format(DATE_ATOM),  // date_format($this->createDate, DateTime::ATOM),
            $this->senderField() => $this->sender,
            $this->receiverField() => $this->receiver,
            $this->messageField() => $this->message,
        );

        return $data;
    }

    public function toJson(): array
    {
        $data = array(
            $this->idJsonKey() => (int) $this->id,
            $this->messageTypeJsonKey() => $this->messageType,
            $this->createDateJsonKey() => $this->createDate->format(DateTime::ATOM),
            $this->senderJsonKey() =>  $this->sender,
            $this->receiverJsonKey() => $this->receiver,
            $this->messageJsonKey() => $this->message,
        );

        return $data;
    }

    public function  add(): Chat_model
    {
        try {
            //generate key
            $this->id = null;

            $this->db->insert($this->tableName, $this->toArray());

            $data = $this->db->get_where($this->tableName, array(
                $this->idField() => $this->db->insert_id()

            ));
            $result = $data->result();
            return $this->fromRow($result[0]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}

/**
 * @OA\Schema(schema="ComposeChat")
 */
class CustomerRegister
{
    /**
     * @OA\Property()
     * @var int
     */
    public $receiver;

    /**
     * @OA\Property()
     * @var String
     */
    public $message;
}

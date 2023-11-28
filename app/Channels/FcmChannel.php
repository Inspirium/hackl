<?php

namespace App\Channels;

use App\Models\Club;
use App\Models\FireBaseSubscription;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Laravel\Firebase\Facades\Firebase;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidMessagePriority;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\NotificationPriority;

class FcmChannel
{
    const MAX_TOKEN_PER_REQUEST = 500;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Send the given notification.
     *
     * @param  User  $notifiable
     * @param  Notification  $notification
     * @return array
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var Collection $tokens */
        $tokens = FireBaseSubscription::where('user_id', $notifiable->id)->whereNotNull('token')->get();
        $subs = [];
        foreach ($tokens as $sub) {
            $subs[] = $sub['token'];
        }
        if (empty($subs)) {
            return [];
        }

        // Get the message from the notification class
        /** @var FcmMessage $fcmMessage */
        $fcmMessage = $notification->toFcm($notifiable);
        if (! $fcmMessage instanceof Message) {
            throw new CouldNotSendNotification('The toFcm() method only accepts instances of '.Message::class);
        }
        $fcmMessage->setAndroid(\NotificationChannels\Fcm\Resources\AndroidConfig::create()
            ->setPriority(AndroidMessagePriority::HIGH)
            ->setNotification(\NotificationChannels\Fcm\Resources\AndroidNotification::create()
                ->setColor('#E6D82F')
                ->setSound('ball.ogg')
                ->setChannelId('tennis-plus')
            )
        );
        $sound = 'ball.aiff';
        $fcmMessage->setApns(ApnsConfig::create()->setPayload(['aps' => ['badge' => $notifiable->unreadNotifications()->count(), 'sound' => $sound]]));
        $responses = [];
        // $link = $fcmMessage->getWebpush()->getFcmOptions()->getLink();
        //foreach ($subs as $club_id => $token) {
            // Use multicast because there are multiple recipients
            $partialTokens = array_chunk($subs, self::MAX_TOKEN_PER_REQUEST, false);
            foreach ($partialTokens as $tks) {
                /** @var MulticastSendReport $r */
                $r = $this->sendToFcmMulticast($fcmMessage, $tks);
                foreach($r->invalidTokens() as $token) {
                    FireBaseSubscription::where('token', $token)->delete();
                }
                $responses[] = $r;
            }
        //}
        return $responses;
    }

    /**
     * @param  Message  $fcmMessage
     * @return mixed
     *
     * @throws CouldNotSendNotification
     */
    protected function sendToFcm(Message $fcmMessage)
    {
        try {
            return Firebase::messaging()->send($fcmMessage);
        } catch (MessagingException $messagingException) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($messagingException);
        }
    }

    /**
     * @param $fcmMessage
     * @param $tokens
     * @return mixed
     *
     * @throws CouldNotSendNotification
     */
    protected function sendToFcmMulticast($fcmMessage, $tokens)
    {
        try {
            return Firebase::messaging()->sendMulticast($fcmMessage, $tokens);
        } catch (MessagingException $messagingException) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($messagingException);
        }
    }
}

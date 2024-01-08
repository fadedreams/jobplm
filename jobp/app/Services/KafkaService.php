<?php
// app/Services/KafkaService.php

namespace App\Services;

use RdKafka\Producer;

class KafkaService
{
    public function sendToKafka(array $messagePayload)
    {
        $conf = new \RdKafka\Conf();
        $conf->set('metadata.broker.list', 'kafka:9092');

        $producer = new Producer($conf);
        $topic = $producer->newTopic('default');

        // Example payload
        $payload = json_encode($messagePayload);

        // Produce a single message
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);

        // Wait for any outstanding messages to be delivered and delivery reports received.
        $producer->flush(10000); // 10 seconds timeout

        // Log the result or perform any other necessary actions
        //
        $logMessage = 'Message produced to Kafka topic: default';
        return $logMessage;
    }
}

<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Ozon\Support\Api\Message\History\Tests;

use BaksDev\Ozon\Support\Api\Message\History\OzonChatHistoryDTO;
use BaksDev\Ozon\Support\Api\Message\History\OzonChatHistoryRequest;
use BaksDev\Ozon\Type\Authorization\OzonAuthorizationToken;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group ozon-support
 * @group ozon-support-api
 */
#[When(env: 'test')]
class OzonChatHistoryRequestTest extends KernelTestCase
{
    private static OzonAuthorizationToken $Authorization;

    public static function setUpBeforeClass(): void
    {
        self::$Authorization = new OzonAuthorizationToken(
            new UserProfileUid(),
            $_SERVER['TEST_OZON_TOKEN'],
            $_SERVER['TEST_OZON_CLIENT'],
            $_SERVER['TEST_OZON_WAREHOUSE']
        );
    }

    public function testComplete(): void
    {
        /** @var OzonChatHistoryRequest $OzonChatHistoryRequest */
        $OzonChatHistoryRequest = self::getContainer()->get(OzonChatHistoryRequest::class);
        $OzonChatHistoryRequest->TokenHttpClient(self::$Authorization);


        $chats = $OzonChatHistoryRequest->findAll();

        //                dd(iterator_to_array($chats));

        if($chats->valid())
        {
            /** @var OzonChatHistoryDTO $OzonChatHistoryDTO */
            $OzonChatHistoryDTO = $chats->current();

            self::assertNotNull($OzonChatHistoryDTO->getId());
            self::assertIsString($OzonChatHistoryDTO->getId());
        }
        else
        {
            self::assertFalse($chats->valid());
        }

    }
}

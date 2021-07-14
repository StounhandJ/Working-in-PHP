<?php
declare(strict_types=1);

namespace App\Application\Actions;

use _HumbugBox2acd634d137b\Nette\Neon\Exception;
use App\Domain\DomainException\DomainRecordBadRequestException;
use App\Domain\DomainException\DomainRecordForbiddenException;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\DomainException\DomainException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use function DI\string;

abstract class Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     * @throws HttpForbiddenException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainException $e) {
            switch ($e->getCode())
            {
                case 400:
                    throw new HttpBadRequestException($this->request, $e->getMessage());
                case 403:
                    throw new HttpForbiddenException($this->request, $e->getMessage());
                case 404:
                    throw new HttpNotFoundException($this->request, $e->getMessage());
                default:
                    throw new HttpNotFoundException($this->request, $e->getMessage());
            }
        }
    }

    /**
     * @return Response
     * @throws DomainRecordNotFoundException
     * @throws DomainRecordForbiddenException
     * @throws DomainRecordBadRequestException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     * @throws HttpBadRequestException
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param  string $name
     * @return mixed
     */
    protected function queryParam(string $name)
    {
        return $this->request->getQueryParams()[$name]??null;
    }

    /**
     * @return array|object|null
     */
    protected function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @param array|object|null $data
     * @param int $statusCode
     * @return Response
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
}

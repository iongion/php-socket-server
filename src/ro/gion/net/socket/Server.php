<?php
namespace ro\gion\net\socket;

class Server
{
  const DEFAULT_PORT = 4444;
  const DEFAULT_ADDRESS = '127.0.0.1';

  protected $sockServer;
  protected $address;
  protected $port;
  protected $_listenLoop;
  protected $connectionHandler;

  public function __construct(
    $port = self::DEFAULT_PORT,
    $address = self::DEFAULT_ADDRESS
  )
  {
    $this->address = $address;
    $this->port = $port;
    $this->_listenLoop = false;
  }

  public function init()
  {
    $this->_createSocket();
    $this->_bindSocket();
  }

  private function _createSocket()
  {
    $this->sockServer = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($this->sockServer === false) {
      throw new Exception(
        Exception::CANT_CREATE_SOCKET,
        socket_strerror(socket_last_error()) );
    }

    socket_set_option($this->sockServer, SOL_SOCKET, SO_REUSEADDR, 1);
  }

  private function _bindSocket()
  {
    if (socket_bind($this->sockServer, $this->address, $this->port) === false) {
      throw new Exception(
        Exception::CANT_BIND_SOCKET,
        socket_strerror(socket_last_error($this->sockServer)));
    }
  }

  public function setConnectionHandler(Callable $handler)
  {
    $this->connectionHandler = $handler;
  }

  public function listen()
  {
    if (socket_listen($this->sockServer, 5) === false) {
      throw new Exception(
        Exception::CANT_BIND_SOCKET,
        socket_strerror(socket_last_error($this->sockServer)));
    }

    $this->_listenLoop = true;
    $this->beforeServerLoop();
    $this->serverLoop();

    socket_close($this->sockServer);
  }

  protected function beforeServerLoop()
  {
    printf("Listening on %s:%d...\n", $this->address, $this->port);
  }

  protected function serverLoop()
  {
    while ($this->_listenLoop) {
      if (($client = @socket_accept($this->sockServer)) === false) {
        throw new Exception(
            Exception::CANT_ACCEPT,
            socket_strerror(socket_last_error($this->sockServer)));
        continue;
      }
      $socketClient = new Client($client);
      call_user_func($this->connectionHandler, $socketClient);
    }
  }

}
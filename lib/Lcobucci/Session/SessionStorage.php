<?php
namespace Lcobucci\Session;

use Lcobucci\Session\Errors\ReservedIdentifierException;
use Lcobucci\Session\Errors\IdentifierNotFoundException;
use Lcobucci\Session\Errors\StorageNotInitializedException;

class SessionStorage implements \ArrayAccess
{
	/**
	 * Identifier to store objects
	 *
	 * @var string
	 */
	const OBJECTS_IDENTIFIER = 'sess_objects_var';

	/**
	 * Stores if the session was started
	 *
	 * @var bool
	 */
	private $started;

	/**
	 * Stores the session content
	 *
	 * @var array
	 */
	private $storage;

	/**
	 * Stores the reserved identifiers
	 *
	 * @var array
	 */
	private $reservedIdentifiers;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->started = false;
		$this->reservedIdentifiers = array(self::OBJECTS_IDENTIFIER);
	}

	/**
	 * Class destructor
	 */
	public function __destruct()
	{
		if ($this->isStarted()) {
			$this->serializeObjects();
			$_SESSION = $this->storage;
		}
	}

	/**
	 * Starts the session (only if is not started)
	 *
	 * @param string $sessionName
	 */
	public function start($sessionName = null)
	{
		if (!$this->isStarted()) {
			if ($sessionName) {
				session_name($sessionName);
			}

			session_start();
			$this->started = true;
			$this->storage = $_SESSION;

			$this->unserializeObjects();
		}
	}

	/**
	 * Terminates the session
	 *
	 * @throws StorageNotInitializedException
	 */
	public function terminate()
	{
		if (!$this->isStarted()) {
			throw new StorageNotInitializedException(
				'You cannot terminate a session that have not been started'
			);
		}

		session_unset();
		session_destroy();
		$this->started = false;
		$this->storage = null;
	}

	/**
	 * Verifies if the identifier exists
	 *
	 * @param string $identifier
	 * @return boolean
	 * @throws StorageNotInitializedException
	 */
	public function exists($identifier)
	{
		if (!$this->isStarted()) {
			throw new StorageNotInitializedException('Session was not started yet');
		}

		return isset($this->storage[$identifier]);
	}

	/**
	 * Verifies if the identifier exists
	 *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
    	return $this->exists($offset);
    }

	/**
	 * Retorns the stored data
	 *
	 * @param string $identifier
	 * @throws IdentifierNotFoundException
	 * @throws ReservedIdentifierException
	 */
	public function get($identifier)
	{
		if (!$this->exists($identifier)) {
			throw new IdentifierNotFoundException('Identifier was not found');
		}

		if ($this->isReserved($identifier)) {
			throw new ReservedIdentifierException('That identifier is reserved');
		}

		return $this->storage[$identifier];
	}

	/**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
    	return $this->get($offset);
    }

	/**
	 * Stores the data
	 *
	 * @param string $identifier
	 * @param mixed $value
	 * @throws StorageNotInitializedException
	 * @throws ReservedIdentifierException
	 */
	public function set($identifier, $value)
	{
		if (!$this->isStarted()) {
			throw new StorageNotInitializedException('Session was not started yet');
		}

		if ($this->isReserved($identifier)) {
			throw new ReservedIdentifierException('That identifier is reserved');
		}

		if ($this->exists($identifier)) {
			$this->storage[$identifier] = null;
		}

		if (is_object($value)) {
			$this->addObject($identifier);
		}

		$this->storage[$identifier] = $value;
	}

	/**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
    	$this->set($offset, $value);
    }

	/**
	 * Remove the identifier
	 *
	 * @param string $identifier
	 * @throws AppSessionException
	 */
	public function remove($identifier)
	{
		if (!$this->exists($identifier)) {
			throw new IdentifierNotFoundException('Identifier was not found');
		}

		if ($this->isReserved($identifier)) {
			throw new ReservedIdentifierException('That identifier is reserved');
		}

		if ($this->isObject($identifier)) {
			$this->removeObject($identifier);
		}

		$this->set($identifier, null);
		unset($this->storage[$identifier]);
	}

	/**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
    	$this->remove($offset);
    }

    /**
     * Returns if the storage is started
     *
     * @return boolean
     */
    protected function isStarted()
    {
    	return $this->started;
    }

	/**
	 * Verifies if the identifier is reserved
	 *
	 * @param string $identifier
	 * @return boolean
	 */
	protected function isReserved($identifier)
	{
		return in_array($identifier, $this->reservedIdentifiers);
	}

	/**
	 * Serilialize the objects (only if exists)
	 */
	protected function serializeObjects()
	{
		if (count($this->getObjects()) > 0) {
			foreach ($this->getObjects() as $identifier) {
				$this->storage[$identifier] = serialize($this->storage[$identifier]);
			}

			$this->storage[self::OBJECTS_IDENTIFIER] = $this->getObjects();
		} elseif ($this->exists(self::OBJECTS_IDENTIFIER)) {
			unset($this->storage[self::OBJECTS_IDENTIFIER]);
		}
	}

	/**
	 * Unserilialize the objects (only if exists
	 */
	protected function unserializeObjects()
	{
		if (count($this->getObjects()) > 0) {
			foreach ($this->getObjects() as $identifier) {
				$this->storage[$identifier] = unserialize($this->storage[$identifier]);
			}
		}
	}

	/**
	 * Verify if the identifier is an object
	 *
	 * @param string $identifier
	 * @return boolean
	 */
	public function isObject($identifier)
	{
		return in_array($identifier, $this->getObjects());
	}

	/**
	 * Returns the identifier of the stored objects
	 *
	 * @return array
	 */
	protected function getObjects()
	{
		return isset($this->storage[self::OBJECTS_IDENTIFIER]) ? $this->storage[self::OBJECTS_IDENTIFIER] : array();
	}

	/**
	 * Remove the identifier from object stack
	 *
	 * @param string $identifier
	 */
	protected function removeObject($identifier)
	{
		foreach ($this->getObjects() as $i => $name) {
			if ($identifier == $name) {
				unset($this->storage[self::OBJECTS_IDENTIFIER][$i]);
			}
		}
	}

	/**
	 * Adiciona a marcação de objeto a um identificador
	 *
	 * @param string $identifier
	 */
	protected function addObject($identifier)
	{
		if (!isset($this->storage[self::OBJECTS_IDENTIFIER])) {
			$this->storage[self::OBJECTS_IDENTIFIER] = array();
		}

		if (!$this->isObject($identifier)) {
			$this->storage[self::OBJECTS_IDENTIFIER][] = $identifier;
		}
	}
}
# DBFunctions
Library with functions for systems using mysql. These will allow a better control and maintenance of the database 
queries along with facilitating the maintenance of connections.

## Content
This project contains one main branch. Being open source, anyone can send pull requests to this main branch to 
integrate whatever they consider necessary, from new features to bug fixes. These pull requests will be reviewed by 
the code owner and approved or commented for possible revisions. The ideal is to be as standard as possible when 
programming in order to make the code understandable by everyone, no matter who develops it.

## Integration
Integrating and starting to use this library is quite simple. Already having the library in your files you can make 
use of this, the only condition that must be met is the correct completion of the connection to the database, an 
element that is basic to take into account in any software.<br>
Also the ``$_SESSION`` variable must have been initialized using the ``session_start()`` function.

When the connection is made, the ``mysqli_connection`` variable is stored in the ``$_SESSION`` environment variable, 
this way it is not necessary to always have the connection variable at hand to make a query.<br>
Likewise, you can maintain multiple active sessions, but you must be careful not to lose the main connection.
This can be avoided by obtaining it using the ``DBGetConnection`` function, this will return the currently active 
connection, allowing you to save it and use it again later without having to disconnect and reconnect to the database.

## Example
You can find a working example of how to use this library in the index.php of the project.<br>
It contains the main functions to perform the queries, connections and error handling you need in your system.

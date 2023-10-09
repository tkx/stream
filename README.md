Enter Stream Library - convenient array mutation and filtering conveyor, that takes the pain of working with traversable data in PHP.
 
Create Streams using "of" factory method, providing any suitable input, then chain mutator methods to transform
your data, then collect it applying any terminal method.

Backed by PHP generators, all chained methods are lazy, thus traversing your data only once when you collect it.

Write complex but clear transformation conveyors.

Collect tailored results with terminal methods.

Save your time and efforts and give focus to creation, rather than routine.

Extend the library easily with your own awesome custom logic!
As Stream Library strictly follows SOLID principles and Design Patterns -
just extend Stream or Terminal class within Moteam\Stream\Library\namespace and your methods will automatically be available by the prefix name of the class!
 
Inspired by Java Stream API, Javascript Underscore and Lodash libraries, and numerous PHP array libraries.
.. _devguide_event_function_trigger_events_obs:

OBS Event Source
================

For the use of OBS triggers, please refer to
:docs_otc:`Using an OBS Trigger <function-graph/umn/creating_triggers/using_an_obs_trigger.html>`.

.. note::
   - For each OBS bucket, only one FunctionGraph can be triggered (no multiple
     FunctionGraphs listening on same bucket).

OBS example event
-----------------

.. literalinclude:: /../../fg-events/fg-obss3-event/resources/obss3_event.json
    :language: json
    :caption: :github_repo_master:`obss3_event.json <fg-events/fg-obss3-event/resources/obss3_event.json>`


Parameter description
---------------------

.. list-table::
   :header-rows: 1
   :widths: 20 15 35

   * - Parameter
     - Type
     - Description
   * - eventVersion
     - String
     - Event version
   * - awsRegion
     - String
     - AWS region
   * - eventTime
     - String
     - Time when an event occurs
   * - eventName
     - String
     - See below
   * - userIdentity
     - Object
     - User identity information
   * - requestParameters
     - Object
     - Request parameters
   * - responseElements
     - String
     - Response elements
   * - s3
     - Object
     - See below

Possible values for eventName
-----------------------------

.. list-table::
   :header-rows: 1
   :widths: 40 60

   * - EventName
     - Description
   * - | ObjectCreated:Put
       | ObjectCreated:Post
       | ObjectCreated:Copy       
     - Operations such as **PUT**, **POST**, and **COPY** can create an object. With these
       event types, you can enable notifications when an object is created
       using a specific API operation.

   * - ObjectCreated:CompleteMultipartUpload
     - **ObjectCreated:CompleteMultipartUpload** includes objects that are created
       using UploadPartCopy for Copy operations.

   * - | ObjectRemoved:Delete
       | ObjectRemoved:DeleteMarkerCreated
     - By using the **ObjectRemoved** event types, you can enable notification when
       an object or a batch of objects is removed from a bucket. You can
       request notification when an object is deleted or a versioned object is
       permanently deleted by using the **ObjectRemoved:Delete** event type.

       Alternatively, you can request notification when a delete marker is
       created for a versioned object using
       **ObjectRemoved:DeleteMarkerCreated**. These event notifications don't
       alert you for automatic deletes from lifecycle configurations or from
       failed operations.

Parameter requestParameters
---------------------------

.. list-table::
   :header-rows: 1
   :widths: 25 15 30

   * - Parameter
     - Type
     - Description
   * - sourceIPAddress
     - String
     - Source IP address

Parameter s3
------------

.. list-table::
   :header-rows: 1
   :widths: 25 15 30

   * - Parameter
     - Type
     - Description
   * - object
     - Object
     - See below
   * - configurationId
     - String
     - Configuration ID
   * - bucket
     - Object
     - See below

Parameter bucket
----------------

.. list-table::
   :header-rows: 1
   :widths: 25 15 30

   * - Parameter
     - Type
     - Description
   * - name
     - String
     - Name of the bucket
   * - ownerIdentity
     - Object
     - Owner identity information
   * - arn
     - String
     - Amazon Resource Name

Parameter object
----------------

.. list-table::
   :header-rows: 1
   :widths: 25 15 30

   * - Parameter
     - Type
     - Description
   * - key
     - String
     - The name that has been assigned to an object.
   * - eTag
     - String
     - The entity tag is a hash of the object.
   * - size
     - Long
     - Size in bytes of the object
   * - sequencer
     - String
     - Sequencer

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-obss3/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-obss3/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-obss3`.


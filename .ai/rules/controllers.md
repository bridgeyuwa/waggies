# Cruddy by Design — Controller and Resource Architecture

Waggies follows the **Cruddy by Design** approach presented by Adam Wathan in the Laracon US 2017 `laracon2017` repository.

The central idea is to keep controllers small by organizing them around **resources** and the seven conventional REST/CRUD controller actions:

* `index`
* `show`
* `create`
* `store`
* `edit`
* `update`
* `destroy`

Do not begin by asking, “What controller method should I add for this feature?”

Begin by asking:

> **What resource is the user interacting with, and which of the seven standard actions describes what is happening?**

The purpose is not CRUD for its own sake. The purpose is to use resource boundaries to keep controllers understandable, small, and focused, while often revealing useful concepts in the domain.

## 1. Prefer the seven standard resource actions

Controllers should ordinarily contain only these actions:

* `index`
* `show`
* `create`
* `store`
* `edit`
* `update`
* `destroy`

Do not casually add custom verb-like methods such as:

* `search()`
* `calculate()`
* `approve()`
* `reject()`
* `submit()`
* `confirm()`
* `complete()`
* `cancel()`
* `publish()`
* `unpublish()`
* `subscribe()`
* `unsubscribe()`
* `resend()`
* `toggle()`
* `process()`
* `export()`

When such a method appears necessary, first reconsider the resource model and route design.

The preferred response to a custom operation is usually **not** to add another method to the existing controller. Instead, identify the resource that is being created, displayed, edited, or destroyed and give that resource its own controller.

## 2. A resource does not necessarily mean an Eloquent model

A resource in this architecture is a meaningful thing the application interacts with.

It does **not** have to correspond one-to-one with an Eloquent model or database table.

A resource can be:

* an Eloquent-backed entity
* a nested resource
* an independently editable property
* a pivot/intermediate record
* a representation of an entity in a particular state
* another meaningful application concept

Do not constrain the HTTP resource design merely because the database has a particular table structure.

The resource model should describe the application's interaction model, not simply mirror database tables.

## 3. Nested resources get dedicated controllers

When a resource is nested under another resource, give the nested context its own controller when the nested operation represents a distinct user action.

For example:

`/podcasts/{podcast}/episodes`

should not automatically be forced into `EpisodesController@index` merely because `Episode` is already a resource.

A route such as:

`/episodes`

and a route such as:

`/podcasts/{podcast}/episodes`

can represent different operations, even though both concern episodes.

Do not reuse one controller action for two substantially different user actions simply to avoid creating another controller.

A dedicated controller such as:

`PodcastEpisodesController`

can own the nested resource actions.

This keeps the action semantics clear and prevents one controller method from having to infer what the user intended from optional route parameters.

## 4. Do not reuse one action for multiple unrelated use cases

Never make a single controller action perform substantially different operations based on whether a route parameter happens to exist.

Avoid patterns such as:

```php
public function index($id = null)
{
    if ($id) {
        // one completely different operation
    } else {
        // another completely different operation
    }
}
```

This is especially problematic when the two branches:

* load different models
* perform different authorization checks
* return different views
* provide different data
* represent different user intentions

A controller action should have one coherent meaning.

When two operations are meaningfully different, create separate resource boundaries and separate controller actions.

## 5. Each controller should have a coherent resource identity

A controller should not casually receive route identifiers representing different resource types depending on which action is executing.

For example, avoid a situation where:

```php
EpisodesController@show($id)
```

interprets `$id` as an episode ID while:

```php
EpisodesController@create($id)
```

interprets `$id` as a podcast ID.

That makes the controller's identity ambiguous.

When nested resources are involved, prefer a dedicated controller whose route parameters consistently describe the resource context it owns.

This creates a stronger mental model:

```text
EpisodesController
    → Episode resource

PodcastEpisodesController
    → Episodes belonging to a Podcast
```

## 6. Independently edited properties can become resources

When one property of an entity is edited independently from the rest of that entity, consider treating that property as its own resource.

For example, suppose a podcast has:

* title
* description
* website
* cover image

and the cover image has its own separate form and endpoint.

Do not automatically add:

```php
updateCoverImage()
```

to `PodcastsController`.

Instead, model the independently managed thing as a resource:

```text
PodcastCoverImageController@update
```

with a resource-oriented endpoint such as:

```text
PUT /podcasts/{podcast}/cover-image
```

The important question is:

> What resource is actually being updated by this operation?

A database column being part of the `podcasts` table does not mean the HTTP resource must be the entire podcast.

## 7. Pivot records can become first-class resources

When a many-to-many relationship contains behavior that needs to be created or destroyed independently, consider whether the pivot record is actually a meaningful resource.

For example:

```text
User ↔ Podcast
```

may produce a pivot record representing:

```text
Subscription
```

Instead of creating custom methods:

```php
subscribe()
unsubscribe()
```

recognize that the application now has a meaningful `Subscription` resource.

Then model the operations as:

```text
SubscriptionsController@store
SubscriptionsController@destroy
```

The underlying database implementation may initially look like a pivot table, but the application's domain may have revealed that the relationship itself is a meaningful thing.

This is an important Cruddy by Design principle:

> A meaningful resource may emerge from a relationship rather than from an obvious standalone model.

When appropriate, promote such a pivot/intermediate record to an explicit model and resource.

## 8. Use “What do I have now that I didn't have before?”

When deciding which CRUD action represents a custom operation, ask:

> **After this operation, what do I have now that I didn't have before?**

This is one of the key mental models from the presentation.

Examples:

```text
subscribe
→ now I have a Subscription
→ SubscriptionsController@store

unsubscribe
→ I no longer have that Subscription
→ SubscriptionsController@destroy
```

Another example:

```text
publish
→ now I have a Published Podcast
→ PublishedPodcastsController@store

unpublish
→ I no longer have a Published Podcast
→ PublishedPodcastsController@destroy
```

Do not focus only on the verb the user interface uses.

Focus on the resource that exists before and after the operation.

## 9. Different states can be modeled as different resources

A state transition does not necessarily belong inside the controller for the underlying entity.

When an entity has a meaningful state that users interact with as a distinct concept, consider treating that state as a resource.

For example:

```text
Podcast
Published Podcast
```

Publishing can therefore be modeled as creating a `PublishedPodcast` resource:

```text
POST /published-podcasts
PublishedPodcastsController@store
```

Unpublishing can be modeled as destroying that resource:

```text
DELETE /published-podcasts/{id}
PublishedPodcastsController@destroy
```

This does not necessarily mean the database needs a separate `published_podcasts` table.

The state-specific resource can operate on the underlying podcast.

The important distinction is between the **HTTP/resource model** and the underlying persistence model.

## 10. Do not cram multiple operations into an existing update action

Do not assume that because two operations modify the same database row, they should share the same `update()` method.

For example, these may both update a podcast record internally:

```text
edit podcast details
publish podcast
```

That does not automatically make them the same HTTP operation.

If they represent different resources or different user intentions, give them separate resource boundaries.

The objective is to prevent an `update()` action from becoming a giant dispatcher for unrelated operations.

## 11. Do not add custom actions merely because they are convenient

Convenience is not sufficient justification for:

```php
public function approve()
public function publish()
public function calculate()
public function submit()
```

Before adding a custom action, work through this sequence:

1. What resource is the user interacting with?
2. Is something being listed?
3. Is something being viewed?
4. Is a creation form being displayed?
5. Is a resource being created?
6. Is an edit form being displayed?
7. Is a resource being updated?
8. Is a resource being destroyed?
9. Is a nested resource involved?
10. Is an independently managed property actually its own resource?
11. Is a relationship/pivot record actually its own resource?
12. Is a meaningful state being treated as a separate resource?

Only after those possibilities have been considered should a custom controller action be considered.

## 12. Smaller controllers are a deliberate architectural outcome

The purpose of Cruddy by Design is not to minimize the raw number of controller classes.

It is to prevent controllers from becoming bloated collections of unrelated operations.

Prefer:

```text
PodcastsController
PodcastEpisodesController
PodcastCoverImageController
SubscriptionsController
PublishedPodcastsController
```

over one enormous:

```text
PodcastsController
```

containing dozens of methods.

It is acceptable, and often desirable, to have more controllers when each controller is responsible for a smaller, coherent resource.

A controller having only one, two, three, or four actions is completely valid.

Do not measure good architecture by the number of controllers alone.

## 13. One controller does not need all seven actions

The seven actions are a vocabulary, not a requirement that every controller implement every method.

Valid examples include:

```php
class PodcastCoverImageController
{
    public function update(...)
    {
    }
}
```

or:

```php
class SubscriptionsController
{
    public function store(...)
    {
    }

    public function destroy(...)
    {
    }
}
```

A resource controller should contain only the actions that make sense for that resource.

Do not create meaningless `index`, `create`, `show`, `edit`, or other methods merely to complete the seven.

## 14. Resource-oriented routes should match the resource

Prefer routes whose URI and HTTP verb communicate the resource operation.

Examples:

```text
GET     /podcasts
GET     /podcasts/{podcast}
GET     /podcasts/create
POST    /podcasts
GET     /podcasts/{podcast}/edit
PUT     /podcasts/{podcast}
DELETE  /podcasts/{podcast}
```

For a nested resource:

```text
GET     /podcasts/{podcast}/episodes
POST    /podcasts/{podcast}/episodes
GET     /podcasts/{podcast}/episodes/create
```

For an independently managed property:

```text
PUT     /podcasts/{podcast}/cover-image
```

For a relationship resource:

```text
POST    /subscriptions
DELETE  /subscriptions/{subscription}
```

For a state resource:

```text
POST    /published-podcasts
DELETE  /published-podcasts/{publishedPodcast}
```

The exact URI design may vary according to the application's resource model, but resource semantics should remain clear.

## 15. Route parameters must have one clear meaning

Avoid overloaded parameters whose meaning changes depending on the controller action.

Prefer:

```php
function show(Episode $episode)
```

for an episode resource and:

```php
function index(Podcast $podcast)
```

for a podcast-scoped episode resource.

The important architectural principle is not the specific Laravel route-model-binding syntax.

The principle is that the resource context should be explicit and unambiguous.

## 16. Request bodies can identify source resources

When a new resource is created from an existing resource, it can be appropriate for the request body to contain the identifier of the source resource.

For example, creating a subscription may require:

```text
podcast_id
```

Creating a state-specific resource may require:

```text
podcast_id
```

The fact that the source resource ID is in the request body rather than the URI does not make the operation non-CRUD.

The question remains whether the request is creating, updating, or destroying the resource represented by the endpoint.

## 17. Do not confuse UI verbs with resource actions

UI language such as:

```text
Subscribe
Publish
Approve
Cancel
Complete
Archive
Restore
```

does not automatically determine the controller method name.

A button may say:

```text
Publish
```

while the underlying resource operation may be:

```text
PublishedPodcastController@store
```

Likewise, a button may say:

```text
Subscribe
```

while the actual resource operation is:

```text
SubscriptionController@store
```

Translate the user intention into the appropriate resource model instead of mechanically turning every UI verb into a controller method.

## 18. Custom controller actions require architectural justification

Waggies does not impose a blind rule that a custom method can never exist under any circumstances.

However, a custom controller action is an exception and should require a clear architectural reason.

Before introducing one, demonstrate why:

* the operation is not naturally a standard CRUD action;
* another resource would not represent it more clearly;
* a nested resource would not represent it more clearly;
* a separately managed property would not represent it more clearly;
* a relationship/pivot resource would not represent it more clearly;
* a state-specific resource would not represent it more clearly.

Do not add custom actions merely because they are faster to implement.

## 19. Keep business logic out of controllers

Cruddy by Design concerns **resource/controller structure**. It does not mean controllers should contain all the application logic.

Controllers should remain thin.

They should primarily:

* receive HTTP input
* authorize the operation
* validate/input the request through the appropriate Laravel mechanisms
* invoke the appropriate application/domain behavior
* return the appropriate response

Substantial business rules should not accumulate inside controllers.

Examples that should generally not become large controller implementations:

* pricing rules
* availability calculations
* booking state machines
* quote calculations
* payment logic
* inventory rules
* domain invariants
* complex workflow orchestration

Resource-oriented controllers and good domain/application design are complementary.

## 20. Cruddy by Design is a resource-design technique, not a database-design rule

Do not assume that every controller must correspond to:

```text
one controller = one table = one Eloquent model
```

That is not the goal.

The resource exposed by the HTTP layer can be a useful conceptual boundary even when it:

* uses an existing model
* combines existing models
* represents a nested relationship
* represents a pivot record
* represents one property
* represents a particular state

Design the resource boundary according to the application's behavior.

## 21. Do not create giant “god controllers”

Avoid controllers that gradually accumulate methods such as:

```text
index
show
create
store
edit
update
destroy
publish
unpublish
approve
reject
calculate
send
resend
cancel
complete
archive
restore
subscribe
unsubscribe
duplicate
export
import
search
```

This is precisely the situation Cruddy by Design is intended to prevent.

When a controller starts accumulating operations outside its resource's natural CRUD lifecycle, stop and reconsider the resource model.

## 22. Preferred decision process

When implementing a new HTTP operation, use this mental sequence:

```text
1. Identify the user's actual operation.

2. Identify what resource is being acted upon.

3. Determine what exists before the operation.

4. Determine what exists after the operation.

5. Ask:
   "What do I have now that I didn't have before?"

6. Map the operation to:
   index
   show
   create
   store
   edit
   update
   destroy

7. If it does not fit:
   reconsider the resource boundary.

8. Check for:
   - nested resource
   - independently edited property
   - relationship/pivot resource
   - state-specific resource

9. Create a dedicated controller when that produces
   a clearer resource boundary.

10. Keep the controller focused on that resource.
```

## 23. Laravel implementation guidance

Use Laravel resource controllers and resource-oriented routing wherever practical.

Prefer:

```php
Route::resource('podcasts', PodcastController::class);
```

or appropriately scoped resource routes where they accurately represent the application's resource model.

Do not use `Route::resource()` mechanically when the resource model is different from a normal seven-action resource. A resource may legitimately expose only a subset of actions.

Likewise, do not avoid creating a dedicated controller simply because the resulting controller name or file count is larger.

The architecture should optimize for **clear resource boundaries and simple controller responsibilities**, not minimum file count.

## 24. Relationship to Waggies architecture

This convention applies to Waggies' HTTP/application design, including future resources such as:

```text
ServiceRequest
Quote
Booking
Customer
Pet
Pricing
Availability
Payment
Inventory
Order
Subscription
```

When these domains become more complex, do not automatically add methods to the primary controller for every lifecycle operation.

Instead, determine whether a new operation represents:

* a standard resource action
* a nested resource
* a distinct resource
* a relationship resource
* a state-specific resource
* an independently managed sub-resource

Then design the controller accordingly.

## 25. Final rule

**Waggies should prefer more small, resource-focused controllers over fewer large controllers with many custom actions.**

The seven conventional actions are the default vocabulary.

When an operation does not fit the existing resource, do not immediately invent a custom controller method. **Redesign the resource boundary first.**

The objective is not to make everything look like CRUD for superficial consistency.

The objective is to use CRUD/resource semantics to uncover meaningful resources and keep controllers small, focused, predictable, and easy to understand.

This is a project-level architectural convention and should be followed consistently unless an explicit later architecture decision supersedes it.

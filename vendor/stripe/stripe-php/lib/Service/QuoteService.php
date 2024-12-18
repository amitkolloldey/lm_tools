<?php

// File generated from our OpenAPI spec

namespace Stripe\Service;

/**
 * @phpstan-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
/**
 * @psalm-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
class QuoteService extends \Stripe\Service\AbstractService
{
    /**
     * Accepts the specified quote.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function accept($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/accept', $id), $params, $opts);
    }

    /**
     * Returns a list of your quotes.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\Quote>
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/quotes', $params, $opts);
    }

    /**
     * When retrieving a quote, there is an includable <a
     * href="https://stripe.com/docs/api/quotes/object#quote_object-computed-upfront-line_items"><strong>computed.upfront.line_items</strong></a>
     * property containing the first handful of those items. There is also a URL where
     * you can retrieve the full (paginated) list of upfront line items.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\LineItem>
     */
    public function allComputedUpfrontLineItems($id, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/computed_upfront_line_items', $id), $params, $opts);
    }

    /**
     * When retrieving a quote, there is an includable <strong>line_items</strong>
     * property containing the first handful of those items. There is also a URL where
     * you can retrieve the full (paginated) list of line items.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\LineItem>
     */
    public function allLineItems($id, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/line_items', $id), $params, $opts);
    }

    /**
     * Retrieves a paginated list of lines for a quote. These lines describe changes
     * that will be used to create new subscription schedules or update existing
     * subscription schedules when the quote is accepted.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\QuoteLine>
     */
    public function allLines($id, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/lines', $id), $params, $opts);
    }

    /**
     * Preview the invoice line items that would be generated by accepting the quote.
     *
     * @param string $parentId
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\InvoiceLineItem>
     */
    public function allPreviewInvoiceLines($parentId, $id, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/preview_invoices/%s/lines', $parentId, $id), $params, $opts);
    }

    /**
     * Preview the invoices that would be generated by accepting the quote.
     *
     * @param string $parentId
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\QuotePreviewInvoice>
     */
    public function allPreviewInvoices($parentId, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/preview_invoices', $parentId), $params, $opts);
    }

    /**
     * Preview the schedules that would be generated by accepting the quote.
     *
     * @param string $parentId
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Collection<\Stripe\QuotePreviewSubscriptionSchedule>
     */
    public function allPreviewSubscriptionSchedules($parentId, $params = null, $opts = null)
    {
        return $this->requestCollection('get', $this->buildPath('/v1/quotes/%s/preview_subscription_schedules', $parentId), $params, $opts);
    }

    /**
     * Cancels the quote.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function cancel($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/cancel', $id), $params, $opts);
    }

    /**
     * A quote models prices and services for a customer. Default options for
     * <code>header</code>, <code>description</code>, <code>footer</code>, and
     * <code>expires_at</code> can be set in the dashboard via the <a
     * href="https://dashboard.stripe.com/settings/billing/quote">quote template</a>.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/quotes', $params, $opts);
    }

    /**
     * Finalizes the quote.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function finalizeQuote($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/finalize', $id), $params, $opts);
    }

    /**
     * Converts a stale quote to draft.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function markDraft($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/mark_draft', $id), $params, $opts);
    }

    /**
     * Converts a draft or open quote to stale.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function markStale($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/mark_stale', $id), $params, $opts);
    }

    /**
     * Download the PDF for a finalized quote.
     *
     * @param string $id
     * @param callable $readBodyChunkCallable
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return mixed
     */
    public function pdf($id, $readBodyChunkCallable, $params = null, $opts = null)
    {
        $opts = \Stripe\Util\RequestOptions::parse($opts);
        if (!isset($opts->apiBase)) {
            $opts->apiBase = $this->getClient()->getFilesBase();
        }

        return $this->requestStream('get', $this->buildPath('/v1/quotes/%s/pdf', $id), $readBodyChunkCallable, $params, $opts);
    }

    /**
     * Recompute the upcoming invoice estimate for the quote.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function reestimate($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s/reestimate', $id), $params, $opts);
    }

    /**
     * Retrieves the quote with the given ID.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/quotes/%s', $id), $params, $opts);
    }

    /**
     * A quote models prices and services for a customer.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \Stripe\Quote
     */
    public function update($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/quotes/%s', $id), $params, $opts);
    }
}

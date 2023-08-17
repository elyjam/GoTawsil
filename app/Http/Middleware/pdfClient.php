<?php

namespace App\Http\Middleware;

use Closure;

class pdfClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $client_id = (!empty(Auth()->user()->ClientDetail->id)) ? Auth()->user()->ClientDetail->id : '';
        $rols = Auth()->user()->role;
        $records = $request->route()->parameters();
        if (!empty($records['facture'])) {
            if ($client_id == $records['facture']['client'] || in_array($rols, array(1, 2, 5, 6, 7, 8, 9))) {
                return $next($request);
            } else {
                abort(403);
            }
        } elseif (!empty($records['expedition'])) {
            if ($client_id == $records['expedition']['client'] || in_array($rols, array(1, 2, 5, 6, 7, 8, 9))) {
                return $next($request);
            } else {
                abort(403);
            }
        } elseif (!empty($records['bon'])) {

            if ($client_id == $records['bon']['id_client'] || in_array($rols, array(1, 2, 5, 6, 7, 8, 9))) {
                return $next($request);
            } else {
                abort(403);
            }
        }elseif (!empty($records['paiement'])) {

            if ($client_id == $records['paiement']['client'] || in_array($rols, array(1, 2, 5, 6, 7, 8, 9))) {
                return $next($request);
            } else {
                abort(403);
            }
        } elseif (!empty($records['remboursement'])) {

            if (in_array($rols, array(1, 2, 5, 6, 7, 8, 9))) {
                return $next($request);
            } else {
                abort(403);
            }
        }
    }
}

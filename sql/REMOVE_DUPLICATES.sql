DELETE FROM public.dubrovka_order
WHERE dubrovka_order.ctid NOT IN 
(
SELECT max(dubrovka_order.ctid)
FROM public.dubrovka_order
GROUP BY dubrovka_order.sd_number)

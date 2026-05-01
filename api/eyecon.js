// api/eyecon.js
export default async function handler(req, res) {
    // --- CORS & Headers ---
    res.setHeader('Content-Type', 'application/json');
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
    res.setHeader('X-Powered-By', 'WASIF ALI');

    // Handle preflight requests
    if (req.method === 'OPTIONS') {
        res.status(200).end();
        return;
    }

    // --- 1. Check if number parameter exists ---
    if (!req.query.number || !req.query.number.trim()) {
        res.status(400).json({
            status: 'error',
            code: 400,
            message: 'Number parameter is required!',
            example: '?number=+923001234567',
            developer: {
                name: 'WASIF ALI',
                telegram: '@FREEHACKS95',
                channel: '@THE_FREE_HACKS'
            }
        });
        return;
    }

    const number = req.query.number.trim();

    // --- 2. Validate number format (must start with +) ---
    const numberRegex = /^\+\d{7,14}$/;
    if (!numberRegex.test(number)) {
        res.status(400).json({
            status: 'error',
            code: 400,
            message: 'Invalid number format! Number must include country code starting with +',
            example: '?number=+923001234567',
            your_input: number,
            developer: {
                name: 'WASIF ALI',
                telegram: '@FREEHACKS95',
                channel: '@THE_FREE_HACKS'
            }
        });
        return;
    }

    // --- 3. Call the Eyecon API ---
    const eyeconUrl = new URL('https://api.eyecon-app.com/app/getnames.jsp');
    eyeconUrl.searchParams.append('cli', number);
    eyeconUrl.searchParams.append('lang', 'en');
    eyeconUrl.searchParams.append('is_callerid', 'true');
    eyeconUrl.searchParams.append('is_ic', 'true');
    eyeconUrl.searchParams.append('cv', 'vc_672_vn_4.2025.10.17.1932_a');
    eyeconUrl.searchParams.append('requestApi', 'URLconnection');
    eyeconUrl.searchParams.append('source', 'MenifaFragment');

    const startTime = Date.now();

    try {
        const apiResponse = await fetch(eyeconUrl, {
            method: 'GET',
            headers: {
                'accept': 'application/json',
                'e-auth-v': 'e1',
                'e-auth': 'c5f7d3f2-e7b0-4b42-aac0-07746f095d38',
                'e-auth-c': '40',
                'e-auth-k': 'PgdtSBeR0MumR7fO',
                'accept-charset': 'UTF-8',
                'content-type': 'application/x-www-form-urlencoded; charset=utf-8',
                'User-Agent': 'EyeconApp/4.5.2 (Android 12; SDK 31)'
            }
        });

        const responseTime = Date.now() - startTime;

        if (!apiResponse.ok) {
            res.status(502).json({
                status: 'error',
                code: 502,
                message: 'Eyecon API returned error',
                http_code: apiResponse.status,
                developer: {
                    name: 'WASIF ALI',
                    telegram: '@FREEHACKS95',
                    channel: '@THE_FREE_HACKS'
                }
            });
            return;
        }

        const eyeconData = await apiResponse.json();

        // --- 4. Send professional response ---
        res.status(200).json({
            status: 'success',
            code: 200,
            timestamp: new Date().toISOString(),
            response_time: `${responseTime}ms`,
            query: {
                number: number,
                country_code: number.substring(0, 3)
            },
            data: eyeconData,
            developer: {
                name: 'WASIF ALI',
                telegram: '@FREEHACKS95',
                channel: '@THE_FREE_HACKS'
            }
        });

    } catch (error) {
        res.status(500).json({
            status: 'error',
            code: 500,
            message: 'Failed to connect to Eyecon API',
            error_details: error.message,
            developer: {
                name: 'WASIF ALI',
                telegram: '@FREEHACKS95',
                channel: '@THE_FREE_HACKS'
            }
        });
    }
}

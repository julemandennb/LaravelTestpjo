export default class Broadcaster
{
    constructor(){}

    leave(channelName) {
         Echo.leave(channelName);
    }

    privateChannel(channelName,listen,callBag)
    {
        Echo.private(channelName)
        .listen(listen, (response) => {

           callBag(response)

        })
    }

}
